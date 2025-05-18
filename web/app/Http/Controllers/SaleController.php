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
            'quantity' => $request->quantity,
            'total' => number_format($totalPrice, 2),
            'time' => now()->timezone('Asia/Manila')->format('h:i A'),
            'updatedTotalProfit' => number_format(
                Sale::whereDate('created_at', today())->sum('total_price'), 2
            ),
            'updatedTotalSold' => Sale::whereDate('created_at', today())->sum('quantity')
        ]);
    }

}
