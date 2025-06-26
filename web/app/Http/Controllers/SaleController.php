<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'discount_type' => 'nullable|string'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Not enough stock'], 400);
        }

        $unitPrice = $product->selling_price;
        $qty = $request->quantity;

        // Normalize discount type
       $discountType = strtoupper($request->discount_type ?? 'NONE');
        $discountType = in_array($discountType, ['SC', 'PWD']) ? $discountType : 'NONE';
        $discountMultiplier = $discountType !== 'NONE' ? 0.8 : 1.0;

        $total = $unitPrice * $qty * $discountMultiplier;

        $sale = Sale::create([
            'product_id' => $product->id,
            'quantity' => $qty,
            'total_price' => $total,
            'discount_type' => $discountType,
        ]);

        $product->decrement('stock', $qty);

        return response()->json([
            'success' => true,
            'message' => 'Sale logged successfully.',
            'id' => $sale->id,
            'product_id' => $product->id,
            'product' => $product->name,
            'quantity' => $qty,
            'discount_type' => $discountType,
            'total' => number_format($total, 2),
            'time' => now()->timezone('Asia/Manila')->format('h:i A'),
            'updatedStock' => $product->stock,
            'updatedTotalProfit' => number_format(Sale::sum('total_price'), 2),
            'updatedTotalSold' => Sale::sum('quantity'),
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
    $query = Sale::with('product');

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
        $totalSold = $daySales->sum('quantity');
        $totalProfit = $daySales->sum(fn($s) => $s->total_price);
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
        $sale->product_id = $lastDeleted['product_id'];
        $sale->quantity = $lastDeleted['quantity'];
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


}
