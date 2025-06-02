@extends('layouts.app')
@section('title', 'Products')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])


<div class="top-bar">
    <form method="GET" action="{{ route('products.index') }}" class="search-form">
        <input type="text" name="search" placeholder="Search drug name or brand..." value="{{ request('search') }}">
        <button type="submit">Search</button>
        @if(request('search'))
            <a href="{{ route('products.index') }}" class="clear-btn">Clear</a>
        @endif
    </form>
    <div class="button-group">
          @php
                $currentCategory = request('category');
                $currentLowStock = request('low_stock') ? ['low_stock' => 1] : [];
                $sortName = request('sort_name');
                $sortExpiry = request('sort_expiry');
            @endphp

            <div class="filter-dropdown-wrapper">
                <button class="filter-btn" onclick="toggleFilterMenu()">Filter</button>
                <div id="filterMenu" class="filter-dropdown">
                    <a href="{{ route('products.index', array_merge(['category' => $currentCategory], $currentLowStock, ['sort_name' => 'asc'])) }}"
                    class="{{ $sortName === 'asc' ? 'active-sort' : '' }}">Sort: Name A–Z</a>

                    <a href="{{ route('products.index', array_merge(['category' => $currentCategory], $currentLowStock, ['sort_name' => 'desc'])) }}"
                    class="{{ $sortName === 'desc' ? 'active-sort' : '' }}">Sort: Name Z–A</a>

                    <a href="{{ route('products.index', array_merge(['category' => $currentCategory], $currentLowStock, ['sort_expiry' => 'asc'])) }}"
                    class="{{ $sortExpiry === 'asc' ? 'active-sort' : '' }}">Sort: Expiry Asc</a>

                    <a href="{{ route('products.index', array_merge(['category' => $currentCategory], $currentLowStock, ['sort_expiry' => 'desc'])) }}"
                    class="{{ $sortExpiry === 'desc' ? 'active-sort' : '' }}">Sort: Expiry Desc</a>

                    <a href="{{ route('products.index', array_merge(['category' => $currentCategory], request()->except('low_stock'), ['low_stock' => request('low_stock') ? null : 1])) }}"
                    class="{{ request('low_stock') ? 'active-sort' : '' }}">
                        {{ request('low_stock') ? 'Show All Stock' : 'Show Low Stock' }}
                    </a>
                </div>
            </div>
            <button class="button-fill green-button" onclick="openAddModal()">+ Add Product</button>
    </div>
</div>


<!-- Category Tabs -->
@php
    $isLowStock = request()->has('low_stock') ? ['low_stock' => 1] : [];
@endphp

<div class="filter-tabs">
   <a href="{{ route('products.index', array_merge([], $isLowStock)) }}"
   class="category-tab {{ request()->get('category') === null ? 'all-active' : '' }}">All</a>

    <a href="{{ route('products.index', array_merge(['category' => 'medicine'], $isLowStock)) }}"
    class="category-tab {{ request()->get('category') === 'medicine' ? 'active' : '' }}">Medicines</a>

    <a href="{{ route('products.index', array_merge(['category' => 'supplies'], $isLowStock)) }}"
    class="category-tab {{ request()->get('category') === 'supplies' ? 'active' : '' }}">Supplies</a>

</div>





<div id="toast" class="toast">Action completed</div>

<!-- Modals -->
<div id="modalOverlay" class="modal-overlay" onclick="closeModal()"></div>
<div id="modalBox" class="modal-box">
    <div class="modal-header">
        <h2 id="modalTitle">Add Product</h2>
        <span class="close-btn" onclick="closeModal()">&times;</span>
    </div>
    <form id="productForm" class="modal-form">
        @csrf
        <input type="hidden" id="formMethod" value="POST">

        <div>
            <label>Drug Name</label>
            <input type="text" name="name" id="inputName" required>
        </div>

        <div>
            <label>Brand</label>
            <input type="text" name="brand" id="inputBrand" required>
        </div>

        <div>
            <label>Supplier Price</label>
            <input type="number" name="supplier_price" id="inputSupplierPrice" step="0.01" required>
        </div>

        <div>
            <label>Selling Price</label>
            <input type="number" name="selling_price" id="inputPrice" step="0.01" required>
        </div>

        <div>
            <label>Stocks</label>
            <input type="number" name="stock" id="inputStock" required>
        </div>

        <div>
            <label>Category</label>
            <select name="category" id="inputCategory" required>
                <option value="medicine">Medicine</option>
                <option value="supplies">Supplies</option>
            </select>
        </div>

        <div class="full-width">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" id="inputExpiryDate" required>
        </div>

        <div class="full-width">
            <button type="submit" id="formButton" class="button-fill blue-button">Add</button>
        </div>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteOverlay" class="modal-overlay" onclick="closeDeleteModal()"></div>
<div id="deleteModal" class="modal-box">
    <div class="modal-header">
        <h2>Confirm Deletion</h2>
        <span class="close-btn" onclick="closeDeleteModal()">&times;</span>
    </div>
    <p id="deleteMessage">Are you sure?</p>
    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
        <button onclick="closeDeleteModal()">Cancel</button>
        <button id="confirmDeleteBtn" class="button-fill red-button">Yes, Delete</button>
    </div>
</div>

<div id="productContainer">
    @include('inventory.partials.table')
</div>

@if(session('deleted_product_id'))
<script>
    window.addEventListener('DOMContentLoaded', () => {
        showToast("{{ session('deleted_product_name') }} deleted.", false, {{ session('deleted_product_id') }});
    });
</script>
@endif

<script>
let deleteTargetId = null;
let currentPage = 1;

function openAddModal() {
    document.getElementById('modalTitle').innerText = 'Add New Product';
    document.getElementById('formButton').innerText = 'Add';
    document.getElementById('productForm').action = '/products';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('inputName').value = '';
    document.getElementById('inputBrand').value = '';
    document.getElementById('inputSupplierPrice').value = '';
    document.getElementById('inputPrice').value = '';
    document.getElementById('inputStock').value = '';
    document.getElementById('inputCategory').value = 'medicine';
    document.getElementById('inputExpiryDate').value = '';
    showModal();
    setTimeout(() => document.getElementById('inputName').focus(), 100);
}

function openEditModal(product) {
    document.getElementById('modalTitle').innerText = 'Edit Product';
    document.getElementById('formButton').innerText = 'Update';
    document.getElementById('productForm').action = `/products/${product.id}`;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('inputName').value = product.name;
    document.getElementById('inputBrand').value = product.brand;
    document.getElementById('inputSupplierPrice').value = product.supplier_price;
    document.getElementById('inputPrice').value = product.selling_price;
    document.getElementById('inputStock').value = product.stock;
    document.getElementById('inputCategory').value = product.category;
    document.getElementById('inputExpiryDate').value = product.expiry_date;
    showModal();
}

function showModal() {
    document.getElementById('modalOverlay').classList.add('show');
    document.getElementById('modalBox').classList.add('show');
}
function closeModal() {
    document.getElementById('modalOverlay').classList.remove('show');
    document.getElementById('modalBox').classList.remove('show');
}

function triggerDelete(id, name) {
    deleteTargetId = id;
    document.getElementById('deleteMessage').textContent = `Are you sure you want to delete "${name}"?`;
    document.getElementById('deleteModal').classList.add('show');
    document.getElementById('deleteOverlay').classList.add('show');
}

function closeDeleteModal() {
    deleteTargetId = null;
    document.getElementById('deleteModal').classList.remove('show');
    document.getElementById('deleteOverlay').classList.remove('show');
}

function undoDelete(id) {
    fetch(`/products/${id}/restore`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message || "Undo complete.");
        location.reload();
    })
    .catch(() => showToast("Undo failed.", true));
}

function showToast(message, isError = false, undoId = null) {
    const toast = document.getElementById('toast');
    toast.innerHTML = message;
    if (undoId) {
        toast.innerHTML += ` <button onclick="undoDelete(${undoId})" style="margin-left:10px;">Undo</button>`;
    }
    toast.style.background = isError ? '#dc3545' : '#28a745';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 5000);
}

document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const url = form.action;
    const method = document.getElementById('formMethod').value;

    const formData = {
        name: document.getElementById('inputName').value,
        brand: document.getElementById('inputBrand').value,
        supplier_price: document.getElementById('inputSupplierPrice').value,
        selling_price: document.getElementById('inputPrice').value,
        stock: document.getElementById('inputStock').value,
        category: document.getElementById('inputCategory').value,
        expiry_date: document.getElementById('inputExpiryDate').value,
        _token: '{{ csrf_token() }}',
        _method: method
    };

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message || 'Saved!');
        closeModal();
        location.reload();
    })
    .catch(() => showToast('Failed to save.', true));
});

document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
    if (!deleteTargetId) return;
    fetch(`/products/${deleteTargetId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ _method: 'DELETE' })
    })
    .then(res => res.ok ? res.json() : Promise.reject(res))
    .then(() => location.reload())
    .catch(() => showToast("Delete failed.", true));
});

// Filters
function toggleFilterMenu() {
    const menu = document.getElementById('filterMenu');
    menu.classList.toggle('show');
}

function applyFilters() {
    const nameSort = document.getElementById('sortName').value;
    const expirySort = document.getElementById('sortExpiry').value;
    const stock = document.getElementById('stockFilter').value;

    const params = new URLSearchParams(window.location.search);
    nameSort ? params.set('sort_name', nameSort) : params.delete('sort_name');
    expirySort ? params.set('sort_expiry', expirySort) : params.delete('sort_expiry');
    stock === 'low' ? params.set('low_stock', '1') : params.delete('low_stock');

    window.location.href = `?${params.toString()}`;
}

// On load tab + pagination logic
document.addEventListener('DOMContentLoaded', function () {
    const lowStockBtn = document.getElementById('filterLowStock');
    const lowStockParam = new URLSearchParams(window.location.search).get('low_stock');
    if (lowStockBtn) {
        lowStockBtn.innerText = lowStockParam === '1' ? 'Show All' : 'Show Low Stock';
    }

    const allTab = document.querySelector('.category-tab.all-active');
    if (allTab) {
        allTab.classList.remove('all-active');
        void allTab.offsetWidth;
        allTab.classList.add('all-active');
    }

    document.addEventListener('click', function (e) {
        const categoryBtn = e.target.closest('.filter-tabs a');
        if (categoryBtn) {
            e.preventDefault();
            const url = new URL(categoryBtn.href);
            if (lowStockBtn?.innerText === 'Show All') {
                url.searchParams.set('low_stock', '1');
            } else {
                url.searchParams.delete('low_stock');
            }

            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const container = document.getElementById('productContainer');
                container.innerHTML = html;
                container.classList.remove('slide-up');
                void container.offsetWidth;
                container.classList.add('slide-up');

                document.querySelectorAll('.category-tab').forEach(tab => tab.classList.remove('active', 'all-active'));
                if (categoryBtn.innerText.trim().toLowerCase() === 'all') {
                    categoryBtn.classList.add('all-active');
                } else {
                    categoryBtn.classList.add('active');
                }
            });
            return;
        }

        const paginationLink = e.target.closest('.pagination a');
        if (paginationLink) {
            e.preventDefault();
            const link = paginationLink.href;
            const page = new URL(link).searchParams.get('page');
            const direction = parseInt(page) > currentPage ? 'slide-left' : 'slide-right';
            const container = document.getElementById('productContainer');

            fetch(link, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                container.innerHTML = html;
                container.classList.remove('slide-left', 'slide-right');
                void container.offsetWidth;
                container.classList.add(direction);
                currentPage = parseInt(page);
            });
        }
    });
});

function toggleFilterMenu() {
    const menu = document.getElementById('filterMenu');
    menu.classList.toggle('show');

    // Close if clicked outside
    document.addEventListener('click', function handleClickOutside(event) {
        if (!menu.contains(event.target) && !event.target.closest('.filter-btn')) {
            menu.classList.remove('show');
            document.removeEventListener('click', handleClickOutside);
        }
    });
}
</script>


@endsection
