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
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'discount_type' => 'nullable|string|in:none,SC,PWD',
        ]);

        // Create Sale
        $sale = Sale::create([
            'discount_type' => $request->discount_type ?? 'none',
        ]);

        $total = 0;

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);

            // Decrease product stock
            if ($product->stock < $item['quantity']) {
                return response()->json(['message' => "Insufficient stock for {$product->name}."], 422);
            }

            $product->stock -= $item['quantity'];
            $product->save();

            // Save each sale item
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
            ]);

            $total += $product->price * $item['quantity'];
        }

        // Apply discount if any
        if ($request->discount_type === 'SC' || $request->discount_type === 'PWD') {
            $total *= 0.8; // 20% off
        }

        $sale->final_total = $total;
        $sale->save();

        return response()->json([
            'id' => $sale->id,
            'message' => 'Sale logged successfully!',
            'discount_type' => $sale->discount_type,
            'total' => number_format($sale->final_total, 2),
            'updatedTotalProfit' => number_format(Sale::sum('final_total'), 2),
            'updatedTotalSold' => SaleItem::sum('quantity'),
            'time' => $sale->created_at->format('h:i A'),
            'product' => 'Multiple', // You can customize this if single item
            'product_id' => $request->items[0]['product_id'],
            'quantity' => array_sum(array_column($request->items, 'quantity')),
            'updatedStock' => Product::find($request->items[0]['product_id'])->stock,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'quantity' => 'required|integer|min:1',
            'original_quantity' => 'required|integer|min:1'
        ]);

        $sale = Sale::findOrFail($request->sale_id);
        $product = Product::findOrFail($sale->product_id);

        $product->increment('stock', $request->original_quantity);

        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Not enough stock to update.'], 400);
        }

        $product->decrement('stock', $request->quantity);

        $sale->update([
            'quantity' => $request->quantity,
            'total_price' => $product->selling_price * $request->quantity,
        ]);

        return response()->json(['success' => 'Sale updated.']);
    }

    public function destroy(Request $request)
    {
        $sale = Sale::findOrFail($request->sale_id);
        $product = Product::findOrFail($sale->product_id);

        // Increment stock
        $product->increment('stock', $sale->quantity);

        // Store sale in session before deletion
        session(['last_deleted_sale' => $sale->toArray()]);

        $sale->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sale deleted successfully.',
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'updatedStock' => $product->stock
        ]);
    }

    public function history(Request $request)
{
    $query = Sale::with('items.product')->orderBy('created_at', 'desc');

    $date = $request->input('date');

    // Fix manually typed d/m/Y format if needed
    if ($date && str_contains($date, '/')) {
        try {
            $date = \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            $date = null;
        }
    }

    if ($date) {
        $query->whereDate('created_at', $date);
    }

    $sales = $query->orderBy('created_at', 'desc')->get()
        ->groupBy(fn($sale) => $sale->created_at->format('Y-m-d'));

    $dailySummary = [];

    foreach ($sales as $date => $daySales) {
        $totalSold = $daySales->sum(fn($sale) => $sale->items->sum('quantity'));
        $totalProfit = $daySales->sum(fn($sale) => $sale->items->sum(fn($i) => $i->quantity * $i->price_per_unit));
        $dailySummary[] = [
            'date' => $date,
            'totalSold' => $totalSold,
            'totalProfit' => $totalProfit,
            'sales' => $daySales,
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

        if (!$lastDeleted || $lastDeleted['id'] != $request->sale_id) {
            return response()->json(['success' => false, 'message' => 'No sale to restore.']);
        }

     $sale = new Sale();
    $sale->discount_type = $lastDeleted['discount_type'];
    $sale->total_price = $lastDeleted['total_price'];
    $sale->created_at = now();
    $sale->updated_at = now();
    $sale->save();

        // Restore stock
        $product = Product::find($sale->product_id);
        $product->stock -= $sale->quantity;
        $product->save();

        session()->forget('last_deleted_sale');

        return response()->json([
            'success' => true,
            'message' => 'Sale restored successfully.'
        ]);
    }

public function fetchItems(Sale $sale)
{
    $sale->load('items.product');

    return response()->json([
        'success' => true,
        'sale' => [
            'id' => $sale->id,
            'items' => $sale->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product->name ?? 'Deleted Product',
                    'quantity' => $item->quantity,
                ];
            })
        ]
    ]);
}

public function updateItems(Request $request)
{
    $data = $request->validate([
        'sale_id' => 'required|exists:sales,id',
        'items' => 'required|array',
        'items.*.item_id' => 'required|exists:sale_items,id',
        'items.*.quantity' => 'required|integer|min:1'
    ]);

    $total = 0;
    foreach ($data['items'] as $item) {
        $salesItem = SalesItem::findOrFail($item['item_id']);
        $salesItem->quantity = $item['quantity'];
        $salesItem->save();

        $total += $salesItem->price_per_unit * $item['quantity'];
    }

    // Reapply discount
    $sale = Sale::find($data['sale_id']);
    if (in_array($sale->discount_type, ['SC', 'PWD'])) {
        $total *= 0.8;
    }

    $sale->total_price = $total;
    $sale->save();

    return response()->json(['success' => true]);
}
public function deleteItem(Request $request)
{
    $request->validate([
        'item_id' => 'required|exists:sale_items,id'
    ]);

    $item = SalesItem::findOrFail($request->item_id);
    $sale = $item->sale;

    $item->delete(); // delete only once

    // Recalculate total
    $newTotal = $sale->items->sum(function ($i) {
        return $i->price_per_unit * $i->quantity;
    });

    if (in_array($sale->discount_type, ['SC', 'PWD'])) {
        $newTotal *= 0.8;
    }

    $sale->total_price = $newTotal;
    $sale->save();

    return response()->json(['success' => true]);
}
}