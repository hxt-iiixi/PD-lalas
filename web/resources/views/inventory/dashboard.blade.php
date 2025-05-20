@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<link href="https://fonts.googleapis.com/css?family=Lexend" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

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
    <div class="d-flex gap-3">
        <div class="log-sale" style="width: 30%;">
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

                    <!-- ✅ Discount Dropdown -->
                    <label for="discount_type">Discount Type:</label>
                    <select name="discount_type" id="discount_type" class="form-select">
                        <option value="none">None</option>
                        <option value="SC">Senior Citizen (20%)</option>
                        <option value="PWD">PWD (20%)</option>
                    </select>

                    <button type="submit" class="button-fill green-button">Log Sale</button>
                </form>
            </div>
        </div>
        <div class="sales-log" style="width: 70%;">
            <h4>Today's Sales Log</h4>

            @if ($todaySales->isEmpty())
                <p>No sales recorded today.</p>
            @else
                <div class="sales-log-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todaySales as $sale)
                                <tr>
                                    <td>{{ $sale->created_at->timezone('Asia/Manila')->format('h:i A') }}</td>
                                    <td>{{ $sale->product->name }}</td>
                                    <td>{{ $sale->quantity }}</td>
                                   <td>{{ $sale->formatted_discount }}</td>
                                    <td>₱{{ number_format($sale->total_price, 2) }}</td>
                                    <td class="action-cell">
                                        <div class="dropdown-wrapper">
                                            <button class="dots-btn" onclick="toggleDropdown(this, event)">⋮</button>
                                            <div class="dropdown-menu">
                                                <a href="#" onclick="openEditModal({{ $sale->id }}, '{{ $sale->product->name }}', {{ $sale->product_id }}, {{ $sale->quantity }}); return false;">Edit</a>
                                                <a href="#" onclick="deleteSale({{ $sale->id }}); return false;">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="chart-section" style="margin-top: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h4>Analytics Overview</h4>
        <select id="chartTypeSelect" class="form-select" style="width: 200px;">
            <option value="profit">Monthly Total Profit</option>
            <option value="sold">Monthly Total Sold</option>
        </select>

    </div>
    <canvas id="analyticsChart" height="100"></canvas>
</div>


<div id="toast" class="toast-message" style="display: none;"></div>


<!-- Sold Details Modal -->
<div id="soldModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeSoldModal()">&times;</span>
    <h3>Products Sold Today</h3>
    <div class="modal-grid">
      <div class="modal-column">
        <h4>Medicines</h4>
        <ul>
          @forelse($soldDetails->filter(fn($item) => $item->product->category === 'medicine') as $item)
            <li>{{ $item->product->name }} – {{ $item->total_quantity }} pcs</li>
          @empty
            <li>No medicines sold.</li>
          @endforelse
        </ul>
      </div>
      <div class="modal-column">
        <h4>Supplies</h4>
        <ul>
          @forelse($soldDetails->filter(fn($item) => $item->product->category === 'supplies') as $item)
            <li>{{ $item->product->name }} – {{ $item->total_quantity }} pcs</li>
          @empty
            <li>No supplies sold.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>



<!-- Stock Alert Modal -->
<!-- Stock Alert Modal -->
<div id="stockModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeStockModal()">&times;</span>
    <h3>Low / Out of Stock Products</h3>
    <div class="modal-grid">
      <!-- Very Low Stock Column -->
      <div class="modal-column">
        <h4 class="modal-header red">Very Low Stock</h4>
        <ul>
          @forelse($lowStock->filter(fn($product) => $product->stock < 20) as $product)
            <li class="red">{{ $product->name }} – {{ $product->stock }} left</li>
          @empty
            <li>None</li>
          @endforelse
        </ul>
      </div>

      <!-- Low Stock Column -->
      <div class="modal-column">
        <h4 class="modal-header orange">Low Stock</h4>
        <ul>
          @forelse($lowStock->filter(fn($product) => $product->stock >= 20) as $product)
            <li class="orange">{{ $product->name }} – {{ $product->stock }} left</li>
          @empty
            <li>None</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Edit Sale Modal -->
<div id="editSaleModal" class="modal">
  <div class="modal-content" style="max-width: 400px;">
    <span class="close" onclick="$('#editSaleModal').hide()">&times;</span>
    <h3>Edit Sale</h3>
    <form id="editSaleForm" style="display: flex; flex-direction: column; gap: 16px;">
        @csrf
        <input type="hidden" name="sale_id" id="editSaleId">
        <input type="hidden" name="original_quantity" id="editOriginalQty">

        <div style="display: flex; flex-direction: column; gap: 6px;">
            <label for="editProductName" style="font-weight: 600; color: #1e293b;">Product</label>
            <input type="text" id="editProductName" disabled
                   style="padding: 10px; border-radius: 10px; border: 1px solid #cbd5e1; background-color: #f9fafb;">
        </div>

        <div style="display: flex; flex-direction: column; gap: 6px;">
            <label for="editQuantity" style="font-weight: 600; color: #1e293b;">Quantity</label>
            <input type="number" name="quantity" id="editQuantity" min="1" required
                   style="padding: 10px; border-radius: 10px; border: 1px solid #cbd5e1;">
        </div>

        <button type="submit" class="button-fill green-button" style="margin-top: 10px;">
            Update Sale
        </button>
    </form>
  </div>
</div>
<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="modal">
  <div class="modal-content" style="max-width: 400px;">
    <span class="close" onclick="$('#deleteConfirmModal').hide()">&times;</span>
    <h3>Confirm Deletion</h3>
    <p>Are you sure you want to delete this sale?</p>
    <div class="modal-actions">
      <button class="button-fill cancel-button" onclick="$('#deleteConfirmModal').hide()">Cancel</button>
      <button class="button-fill delete-button" id="confirmDeleteBtn">Delete</button>
    </div>
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
                form[0].reset();
                button.prop('disabled', false).text('Log Sale');

                // Add new row visually
                // Format discount label
                let discountLabel = 'None';
                if (response.discount_type === 'SC') discountLabel = 'Senior Citizen (20%)';
                else if (response.discount_type === 'PWD') discountLabel = 'PWD (20%)';

                const newRow = `
                    <tr class="highlight-row">
                        <td>${response.time}</td>
                        <td>${response.product}</td>
                        <td>${response.quantity}</td>
                        <td>${discountLabel}</td>
                        <td>₱${response.total}</td>
                        <td class="action-cell">
                            <div class="dropdown-wrapper">
                                <button class="dots-btn">⋮</button>
                                <div class="dropdown-menu">
                                    <a href="#" onclick="openEditModal(${response.id}, '${response.product}', ${response.product_id}, ${response.quantity}); return false;">Edit</a>
                                    <a href="#" onclick="deleteSale(${response.id}); return false;">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;

                $('table.table tbody').prepend(newRow);
                $('.highlight-row').hide().fadeIn(600).removeClass('highlight-row');

                // 🔁 Update total stats
                $('#total-profit').text('₱' + response.updatedTotalProfit);
                $('#total-sold').text(response.updatedTotalSold);

                // 🔁 Update stock in dropdown
                const updatedOption = $(`#logSaleForm select[name="product_id"] option[value="${response.product_id}"]`);
                if (updatedOption.length) {
                    updatedOption.text(`${response.product} (Stock: ${response.updatedStock})`);
                }

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

<script>
let chart;
const ctx = document.getElementById('analyticsChart').getContext('2d');
function loadChartData(type) {
    fetch(`/chart-data/${type}`)
        .then(res => res.json())
        .then(data => {
            console.log("CHART RESPONSE:", data);
            if (chart) chart.destroy();

            const colors = [
                '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                '#14b8a6', '#f43f5e', '#eab308', '#0ea5e9', '#6366f1',
                '#ec4899', '#84cc16'
            ];

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: type === 'profit' ? '₱ Total Profit' :
                               type === 'sold' ? 'Units Sold' : 'Most Sold Products',
                        data: data.values,
                        backgroundColor: colors.slice(0, data.labels.length),
                        borderRadius: 10,
                        barPercentage: 0.7,
                        categoryPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    const label = context.label;
                                    if (context.dataset.label.includes('Profit')) {
                                        return `${label}: ₱${value}`;
                                    } else if (context.dataset.label.includes('Units')) {
                                        return `${label}: ${value} pcs`;
                                    } else {
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
}


document.getElementById('chartTypeSelect').addEventListener('change', function () {
    loadChartData(this.value);
});
loadChartData('profit'); // Load default
</script>

<script>
$(document).on('click', '.edit-sale-btn', function () {
    const btn = $(this);
    $('#editSaleId').val(btn.data('sale-id'));
    $('#editOriginalQty').val(btn.data('quantity'));
    $('#editProductName').val(btn.data('product-name'));
    $('#editQuantity').val(btn.data('quantity'));
    $('#editSaleModal').show();
});

// Submit edit sale
$('#editSaleForm').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
        url: "{{ route('sales.update') }}",
        type: "POST",
        data: formData,
        success: function (res) {
            showToast('Sale updated successfully.', 'green'); // ✅ Toast before reload
            setTimeout(() => location.reload(), 1000); // slight delay so toast appears
        },
        error: function () {
            alert("Failed to update sale.");
        }
    });
});

</script>

<script>
let selectedSaleId = null;
function toggleDropdown(button, event) {
    event.stopPropagation(); // Prevent from bubbling to window

    const menu = $(button).siblings('.dropdown-menu');

    if (menu.hasClass('open')) {
        // Close current menu
        menu.slideUp(150, function () {
            menu.removeClass('open');
        });
    } else {
        // Close any other open menus first
        $('.dropdown-menu.open').slideUp(150).removeClass('open');

        // Open current menu
        menu.slideDown(150, function () {
            menu.addClass('open');
        });
    }
}





$(document).on('click', function (e) {
    if (!$(e.target).closest('.dropdown-wrapper').length) {
        $('.dropdown-menu.open').slideUp(150).removeClass('open');
    }

    // Close modals if background clicked
    ['#soldModal', '#stockModal', '#editSaleModal', '#deleteConfirmModal'].forEach(id => {
        const modal = document.querySelector(id);
        if (modal && e.target === modal) {
            modal.style.display = 'none';
        }
    });
});



// Hook for opening modal
function openEditModal(id, name, productId, quantity) {
    $('#editSaleId').val(id);
    $('#editOriginalQty').val(quantity);
    $('#editProductName').val(name);
    $('#editQuantity').val(quantity);
    $('#editSaleModal').show();
}

// (Optional) deleteSale placeholder
function deleteSale(saleId) {
    selectedSaleId = saleId;
    $('#deleteConfirmModal').show();
}

$('#confirmDeleteBtn').on('click', function () {
    $.ajax({
        url: "{{ route('sales.delete') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            sale_id: selectedSaleId
        },
        success: function (res) {
            if (res.success) {
                // Store toast for reload
                localStorage.setItem('toastMessage', res.message);
                localStorage.setItem('toastColor', 'red');

                location.reload(); // ⬅️ Will trigger toast on next load
            }
            $('#deleteConfirmModal').hide();
        },
        error: function () {
            alert("Failed to delete sale.");
        }
    });
});


</script>
<script>
function showToast(message, color = 'green') {
    const toast = $('#toast');
    toast.text(message);

    toast.css({
        display: 'block',
        backgroundColor: color === 'green' ? '#4ade80' : '#f87171', // green/red
        color: '#1e293b'
    }).addClass('show');

    setTimeout(() => {
        toast.removeClass('show');
        setTimeout(() => toast.css('display', 'none'), 400);
    }, 3000);
}
</script>

<script>
$(document).ready(function () {
    const msg = localStorage.getItem('toastMessage');
    const color = localStorage.getItem('toastColor');

    if (msg) {
        showToast(msg, color || 'green');

        // Clear it after showing
        localStorage.removeItem('toastMessage');
        localStorage.removeItem('toastColor');
    }
});
</script>


@endsection
