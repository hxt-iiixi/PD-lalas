@extends('layouts.app')

@section('title', 'Dashboard')


<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">


@section('content')
    <h1>Top 5 Most Sold Products This Month</h1>

    <div class="card full">
        <canvas id="topProductsChart" width="600" height="400"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('topProductsChart').getContext('2d');
        const topProductsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Quantity Sold',
                    data: {!! json_encode($values) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endsection
