<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use App\Models\SalesItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
{
    $today = Carbon::today();

    // 🔁 Get today's Sales Items (multi-product sales)
    $todaySales = SalesItem::with(['product', 'sale'])
        ->whereDate('created_at', $today)
        ->orderBy('created_at', 'desc')
        ->get();

    // 🔢 Total products
    $totalProducts = Product::count();

    // 💰 Total profit and quantity from SalesItems
    $totalProfit = SalesItem::whereDate('created_at', $today)->sum('total_price');
    $totalSoldQty = SalesItem::whereDate('created_at', $today)->sum('quantity');

    // 🔝 Most sold products today
    $soldDetails = $todaySales
        ->groupBy('product_id')
        ->map(function ($group) {
            return (object)[
                'product' => $group->first()->product,
                'total_quantity' => $group->sum('quantity')
            ];
        })
        ->sortByDesc('total_quantity');

    // 📉 Low stock products
    $lowStock = Product::where('stock', '<', 50)
        ->orderBy('stock', 'asc')
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
