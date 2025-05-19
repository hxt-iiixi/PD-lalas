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
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Not enough stock'], 400);
        }

        $totalPrice = $product->selling_price * $request->quantity;

        Sale::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
        ]);

        $product->decrement('stock', $request->quantity);

        return response()->json([
            'message' => 'Sale recorded successfully.',
            'product' => $product->name,
            'product_id' => $product->id, // ✅ add this
            'quantity' => $request->quantity,
            'total' => number_format($totalPrice, 2),
            'time' => now()->timezone('Asia/Manila')->format('h:i A'),
            'updatedStock' => $product->stock, // ✅ add this
            'updatedTotalProfit' => number_format(
                Sale::whereDate('created_at', today())->sum('total_price'), 2
            ),
            'updatedTotalSold' => Sale::whereDate('created_at', today())->sum('quantity')
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

        // Restore original quantity to stock
        $product->increment('stock', $request->original_quantity);

        // Check if enough stock for new quantity
        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Not enough stock to update.'], 400);
        }

        // Adjust again
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

        // Restore the sold quantity to the product stock
        $product->increment('stock', $sale->quantity);

        $sale->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sale deleted successfully.',
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'updatedStock' => $product->stock
        ]);
    }

}
