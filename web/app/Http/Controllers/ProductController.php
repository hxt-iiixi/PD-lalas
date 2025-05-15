<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->query('search');

        $products = Product::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->paginate(15);

        if ($request->ajax()) {
            return view('inventory.partials.table', compact('products'))->render();
        }

        return view('inventory.index', compact('products'));
    }


    


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'brand' => 'required',
            'selling_price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::create($request->only('name', 'brand', 'selling_price', 'stock'));

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'brand' => 'required',
            'selling_price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product->update($request->only('name', 'brand', 'selling_price', 'stock'));

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully!',
        ]);
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return response()->json(['message' => 'Product restored successfully!']);
    }
}
