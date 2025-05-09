<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Sale;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $products = Product::query()
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%"); // assuming 'category' is brand for now
        })
        ->get();

    return view('inventory.index', compact('products', 'search'));
}


    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'stock' => 'required|integer',
            'category' => 'required',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product added!');
    }

    public function edit(Product $product)
    {
        return view('inventory.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'stock' => 'required|integer',
            'category' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted!');
    }
    

    public function dashboard()
    {
        $topProducts = Sale::selectRaw('product_id, SUM(quantity) as total_sold')
            ->whereMonth('created_at', now()->month)
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();

        $labels = $topProducts->map(fn($sale) => $sale->product->name);
        $values = $topProducts->pluck('total_sold');

        return view('inventory.dashboard', [
            'labels' => $labels,
            'values' => $values,
        ]);
    }
    
}
