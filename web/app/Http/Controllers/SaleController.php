<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\SalesItem;

class SaleController extends Controller
{
   public function store(Request $request)
    {
        $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'discount_type' => 'nullable|in:None,Senior,PWD',
        ]);

        $productIds = $request->input('product_id');
        $quantities = $request->input('quantity');
        $discountType = $request->input('discount_type', 'None');

        $totalPrice = 0;
        $items = [];

        // Loop through each product and calculate total
        foreach ($productIds as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $quantities[$index];

            if ($product->stock < $quantity) {
                return response()->json(['error' => "Insufficient stock for {$product->name}"], 400);
            }

            $itemTotal = $product->price * $quantity;
            $totalPrice += $itemTotal;

            $items[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'total_price' => $itemTotal,
            ];
        }

        // Apply discount to total (if any)
        if ($discountType === 'Senior' || $discountType === 'PWD') {
            $totalPrice *= 0.80; // 20% off
        }

        // Save Sale
        $sale = Sale::create([
            'discount_type' => $discountType,
            'total_price' => $totalPrice,
        ]);

        // Save each item under sales_items table
        foreach ($items as $item) {
            SalesItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_price' => $item['total_price'],
            ]);

            // Deduct stock
            $product = Product::findOrFail($item['product_id']);
            $product->stock -= $item['quantity'];
            $product->save();
        }

        return response()->json(['message' => 'Sale recorded successfully.']);
    }


    public function update(Request $request)
    {
        $request->validate([
            'sales_item_id' => 'required|exists:sales_items,id',
            'quantity' => 'required|integer|min:1',
            'original_quantity' => 'required|integer|min:1'
        ]);

        $salesItem = SalesItem::findOrFail($request->sales_item_id);
        $product = Product::findOrFail($salesItem->product_id);

        $product->increment('stock', $request->original_quantity); // Restore original
        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Not enough stock to update.'], 400);
        }

        $product->decrement('stock', $request->quantity);

        $salesItem->update([
            'quantity' => $request->quantity,
            'total_price' => $product->price * $request->quantity,
        ]);

        // Recompute the total for parent Sale
        $salesItem->sale->total_price = $salesItem->sale->items->sum('total_price');
        $salesItem->sale->save();

        return response()->json(['success' => 'Sales item updated.']);
    }

    public function destroy(Request $request)
    {
        $salesItem = SalesItem::findOrFail($request->sales_item_id);
        $product = Product::findOrFail($salesItem->product_id);

        $product->increment('stock', $salesItem->quantity);
        session(['last_deleted_sale' => $salesItem->toArray()]);

        $salesItem->delete();

        // If sale has no more items, delete it
        if ($salesItem->sale->items()->count() === 0) {
            $salesItem->sale->delete();
        } else {
            $salesItem->sale->total_price = $salesItem->sale->items()->sum('total_price');
            $salesItem->sale->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Sales item deleted successfully.',
            'sale_id' => $salesItem->sale_id,
            'product_id' => $product->id,
            'updatedStock' => $product->stock
        ]);
    }


   public function history(Request $request)
    {
        $date = $request->input('date');

        if ($date && str_contains($date, '/')) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            } catch (\Exception $e) {
                $date = null;
            }
        }

        $query = SalesItem::with(['product', 'sale']);

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        $items = $query->orderBy('created_at', 'desc')->get()
            ->groupBy(fn($item) => $item->created_at->format('Y-m-d'));

        $dailySummary = [];

        foreach ($items as $date => $dayItems) {
            $totalSold = $dayItems->sum('quantity');
            $totalProfit = $dayItems->sum('total_price');

            $dailySummary[] = [
                'date' => $date,
                'totalSold' => $totalSold,
                'totalProfit' => $totalProfit,
                'sales' => $dayItems, // contains individual product sales
            ];
        }

        return view('inventory.history', compact('dailySummary'));
    }


    public function reset()
    {
        \App\Models\Sale::truncate(); // Permanently deletes all sales
        return response()->json(['success' => true, 'message' => 'All sales have been reset.']);
    }

    public function undo(Request $request)
    {
        $lastDeleted = session('last_deleted_sale');

        if (!$lastDeleted || $lastDeleted['id'] != $request->sales_item_id) {
            return response()->json(['success' => false, 'message' => 'No sales item to restore.']);
        }

        $sale = Sale::firstOrCreate([
            'created_at' => now()->toDateString(),
            'discount_type' => 'None',
        ], [
            'total_price' => 0,
        ]);

        $item = new SalesItem();
        $item->sale_id = $sale->id;
        $item->product_id = $lastDeleted['product_id'];
        $item->quantity = $lastDeleted['quantity'];
        $item->price = $lastDeleted['price'];
        $item->total_price = $lastDeleted['total_price'];
        $item->created_at = now();
        $item->save();

        $product = Product::find($item->product_id);
        $product->stock -= $item->quantity;
        $product->save();

        // Update sale total
        $sale->total_price = $sale->items()->sum('total_price');
        $sale->save();

        session()->forget('last_deleted_sale');

        return response()->json([
            'success' => true,
            'message' => 'Sales item restored successfully.'
        ]);
    }


}
