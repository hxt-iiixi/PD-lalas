<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
    {
        $today = Carbon::today();

        $todaySales = Sale::whereDate('created_at', now()->toDateString())
        ->orderBy('created_at', 'desc')
        ->get();


        $totalProducts = Product::count();
        $totalProfit = $todaySales->sum('total_price');
        $totalSoldQty = $todaySales->sum('quantity');

        $soldDetails = $todaySales
        ->groupBy('product_id')
        ->map(function ($group) {
            return (object)[
                'product' => $group->first()->product,
                'total_quantity' => $group->sum('quantity')
            ];
        })
        ->sortByDesc('total_quantity'); // 🔁 Sort by most sold first


       $lowStock = Product::where('stock', '<', 50)
        ->orderBy('stock', 'asc') // 🔁 Sort by lowest stock first
        ->get();


        return view('inventory.dashboard', compact(
            'todaySales',
            'totalProducts',
            'totalProfit',
            'totalSoldQty',
            'soldDetails',
            'lowStock'
        ));
    }

   public function chartData($type)
    {
        // ✅ TEMP: Test block for "profit"
        if ($type === 'profit') {
    return response()->json([
        'labels' => ['Jan', 'Feb', 'Mar'],
        'values' => [100, 200, 150]
    ]);
    } elseif ($type === 'sold') {
        return response()->json([
            'labels' => ['Jan', 'Feb', 'Mar'],
            'values' => [90, 20, 15]
        ]);
    } 


    // fallback (shouldn't be triggered)
    return response()->json(['labels' => [], 'values' => []]);
}

}
