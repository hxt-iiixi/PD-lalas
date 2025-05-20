<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class InventoryController extends Controller
{
    public function history()
    {
        $sales = Sale::with('product')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($sale) {
                return $sale->created_at->format('Y-m-d');
            });

        $dailySummary = [];

        foreach ($sales as $date => $daySales) {
            $totalSold = $daySales->sum('quantity');
            $totalProfit = $daySales->sum('total_price');
            $dailySummary[] = [
                'date' => $date,
                'totalSold' => $totalSold,
                'totalProfit' => $totalProfit,
                'sales' => $daySales,
            ];
        }

        return view('inventory.history', compact('dailySummary'));
    }
}
