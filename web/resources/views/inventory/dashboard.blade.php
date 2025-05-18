@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<link href="https://fonts.googleapis.com/css?family=Lexend" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="dashboard-grid">
   <div class="stat-card blue">
        <i class="fa-solid fa-boxes-stacked stat-icon"></i>
        <div class="stat-header">Total Products</div>
        <div class="stat-count">{{ $totalProducts }}</div>
    </div>

    <div class="stat-card green">
        <i class="fa-solid fa-wallet stat-icon"></i>
        <div class="stat-header">Total Profit</div>
        <div class="stat-count" id="total-profit">₱{{ number_format($totalProfit, 2) }}</div>
    </div>

    <div class="stat-card yellow">
        <i class="fa-solid fa-arrow-trend-up stat-icon"></i>
        <div class="stat-header">Total Sold</div>
        <div class="stat-count" id="total-sold">{{ $totalSoldQty }}</div>
        <a href="#" onclick="openSoldModal()" class="details-link">Show Details</a>
    </div>

    <div class="stat-card red">
        <i class="fa-solid fa-triangle-exclamation stat-icon"></i>
        <div class="stat-header">Out of Stock</div>
        <div class="stat-count">{{ $lowStock->count() }}</div>
        <a href="#" onclick="openStockModal()" class="details-link">Show Details</a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="sales-section">
    <div class="sales-form">
        <h4>Log a Sale</h4>
        <form id="logSaleForm">
            @csrf
            <label for="product_id">Select Product:</label>
            <select name="product_id" required>
                @foreach(App\Models\Product::all() as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                @endforeach
            </select>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" min="1" required>

            <button type="submit" class="button-fill green-button">Log Sale</button>
        </form>
    </div>

    <div class="sales-log">
        <h4>Today's Sales Log</h4>

        @if ($todaySales->isEmpty())
            <p>No sales recorded today.</p>
        @else
            <div class="scrollable-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todaySales as $sale)
                            <tr>
                                <td>{{ $sale->created_at->timezone('Asia/Manila')->format('h:i A') }}</td>
                                <td>{{ $sale->product->name }}</td>
                                <td>{{ $sale->quantity }}</td>
                                <td>₱{{ number_format($sale->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div id="toast" class="toast-message" style="display: none;"></div>


<!-- Sold Details Modal -->
<div id="soldModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeSoldModal()">&times;</span>
    <h3>Products Sold Today</h3>
    <ul>
      @forelse($soldDetails as $item)
        <li>{{ $item->product->name }} – {{ $item->total_quantity }} pcs</li>
      @empty
        <li>No products sold today.</li>
      @endforelse
    </ul>
  </div>
</div>

<!-- Stock Alert Modal -->
<div id="stockModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeStockModal()">&times;</span>
    <h3>Low / Out of Stock Products</h3>
    <ul>
      @forelse($lowStock as $product)
        <li style="color: {{ $product->stock < 20 ? 'red' : '#f59e0b' }}">
          {{ $product->name }} – {{ $product->stock }} left
        </li>
      @empty
        <li>All products are sufficiently stocked.</li>
      @endforelse
    </ul>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Modal functions
    function openSoldModal() {
        document.getElementById('soldModal').style.display = 'block';
    }

    function closeSoldModal() {
        document.getElementById('soldModal').style.display = 'none';
    }

    function openStockModal() {
        document.getElementById('stockModal').style.display = 'block';
    }

    function closeStockModal() {
        document.getElementById('stockModal').style.display = 'none';
    }

    window.onclick = function(event) {
        ['soldModal', 'stockModal'].forEach(id => {
            const modal = document.getElementById(id);
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    };

    // AJAX submit handler
    $('#logSaleForm').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const button = form.find('button');
        button.prop('disabled', true).text('Logging...');

        $.ajax({
            url: "{{ route('sales.store') }}",
            method: "POST",
            data: form.serialize(),
            success: function(response) {
                // Reset form
                form[0].reset();
                button.prop('disabled', false).text('Log Sale');

                // Add new row to table (prepend to top for effect)
                const newRow = `
                    <tr class="highlight-row">
                        <td>${response.time}</td>
                        <td>${response.product}</td>
                        <td>${response.quantity}</td>
                        <td>₱${response.total}</td>
                    </tr>
                `;
                $('table.table tbody').prepend(newRow);
                $('.highlight-row').hide().fadeIn(600).removeClass('highlight-row');

                // 🔁 Update Total Profit and Sold
                $('#total-profit').text('₱' + response.updatedTotalProfit);
                $('#total-sold').text(response.updatedTotalSold);

                // ✅ Show toast
                const toast = $('#toast');
                toast.text(response.message).css('display', 'block').addClass('show');
                setTimeout(() => {
                    toast.removeClass('show');
                    setTimeout(() => toast.css('display', 'none'), 400);
                }, 3000);
            },
            error: function(err) {
                button.prop('disabled', false).text('Log Sale');
                alert('Error logging sale. Please check stock or try again.');
            }
        });
    });
</script>



@endsection
