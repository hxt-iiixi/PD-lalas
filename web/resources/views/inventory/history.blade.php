@extends('layouts.app')
@section('title', 'Sales History')
@section('content')

@vite(['resources/css/app.css', 'resources/js/app.js'])


<style>
    .page-title {
        font-size: 22px;
        font-weight: 600;
        color: #1e293b;
        margin: 20px 0;
        font-family: 'Rubik', sans-serif;
    }

    .history-card {
        margin-bottom: 20px;
        border: 1px solid #ddd;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        background: #fff;
    }

    .summary-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 500;
        font-family: 'Rubik', sans-serif;
    }

    .summary-row button {
        background-color: #0d6efd;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .summary-row button:hover {
        background-color: #084dc1;
    }

    .details-table {
        margin-top: 14px;
        display: none;
    }

    .table-responsive {
        overflow-x: auto;
        border-radius: 8px;
    }

    .table-responsive table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .table-responsive th,
    .table-responsive td {
        border: 1px solid #ddd;
        padding: 8px 12px;
        text-align: center;
    }

    .table-responsive th {
        background-color: #f0f0f0;
    }

    @media (max-width: 600px) {
        .summary-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .summary-row button {
            margin-top: 10px;
        }
    }
</style>

<h1 class="page-title">Sales History</h1>
<form method="GET" class="search-form" style="margin-bottom: 20px;">
    <input type="date" name="date" value="{{ request('date') }}" required>
    <button type="submit" class="button-fill green-button">Search Date</button>
    @if(request()->has('date'))
        <a href="{{ route('sales.index') }}" class="clear-btn">Clear</a>
    @endif
</form>


@foreach ($dailySummary as $day)
    <div class="history-card">
        <div class="summary-row">
            <strong>{{ $day['date'] }}</strong>
            <span>Total Sold: {{ $day['totalSold'] }}</span>
            <span>Total Profit: ₱{{ number_format($day['totalProfit'], 2) }}</span>
            <button onclick="toggleDetails('{{ $day['date'] }}')">Expand</button> 
        </div>

        <div id="details-{{ $day['date'] }}" class="details-table">
            <div class="table-responsive">
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
    </div>
@endforeach

<script>
    function toggleDetails(date) {
        const el = document.getElementById(`details-${date}`);
        const isHidden = el.style.display === 'none' || el.style.display === '';
        el.style.display = isHidden ? 'block' : 'none';

        const button = el.previousElementSibling.querySelector('button');
        if (button) button.innerText = isHidden ? 'Collapse' : 'Expand';
    }
</script>

@endsection
