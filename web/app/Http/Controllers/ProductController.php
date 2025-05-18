<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $lowStock = $request->query('low_stock');
        $sortName = $request->query('sort_name');       // asc or desc
        $sortExpiry = $request->query('sort_expiry');   // asc or desc

        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->when($lowStock, function ($query) {
                $query->where('stock', '<', 21);
            })
            ->when($sortName, function ($query, $sortName) {
                $query->orderBy('name', $sortName);
            })
            ->when($sortExpiry, function ($query, $sortExpiry) {
                $query->orderBy('expiry_date', $sortExpiry);
            })
            // Fallback sort if no sort provided
            ->when(!$sortName && !$sortExpiry, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->paginate(15);

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
            'category' => 'required|in:medicine,supplies',
            'expiry_date' => 'required|date|after:today',
        ]);

        $product = Product::create($request->only([
            'name', 'brand', 'selling_price', 'stock', 'category', 'expiry_date'
        ]));

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
            'expiry_date' => 'required|date|after:today',
        ]);

        $product->update($request->only([
            'name', 'brand', 'selling_price', 'stock', 'expiry_date'
        ]));

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product,
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $productName = $product->name;
        $productId = $product->id;

        $product->delete();

        session()->flash('deleted_product_id', $productId);
        session()->flash('deleted_product_name', $productName);

        return response()->json([
            'message' => "$productName deleted.",
            'undoId' => $productId,
        ]);
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return response()->json(['message' => 'Product restored successfully!']);
    }
}
