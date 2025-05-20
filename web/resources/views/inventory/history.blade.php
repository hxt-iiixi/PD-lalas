@extends('layouts.app')
@section('title', 'Sales History')
@section('content')

<link rel="stylesheet" href="{{ asset('css/products.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">
<h1 class="page-title">Sales History</h1>

@foreach ($dailySummary as $day)
    <div class="history-card">
        <div class="summary-row">
            <strong>{{ $day['date'] }}</strong>
            <span>Total Sold: {{ $day['totalSold'] }}</span>
            <span>Total Profit: ₱{{ number_format($day['totalProfit'], 2) }}</span>
            <button onclick="toggleDetails('{{ $day['date'] }}')">Expand</button>
        </div>

        <div id="details-{{ $day['date'] }}" class="details-table" style="display:none;">
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Total (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($day['sales'] as $sale)
                        <tr>
                            <td>{{ $sale->created_at->format('H:i') }}</td>
                            <td>{{ $sale->product->name }}</td>
                            <td>{{ $sale->quantity }}</td>
                            <td>{{ ucfirst($sale->discount_type) }}</td>
                            <td>{{ number_format($sale->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

<script>
    function toggleDetails(date) {
        const el = document.getElementById(`details-${date}`);
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }
</script>

@endsection
