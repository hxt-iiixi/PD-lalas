@extends('layouts.app')
@section('title', 'Products')
@section('content')
<link href="https://fonts.googleapis.com/css?family=Lexend" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/products.css') }}">

<div class="top-bar">
    <form method="GET" action="{{ route('products.index') }}" class="search-form">
        <input type="text" name="search" placeholder="Search drug name or brand..." value="{{ request('search') }}">
        <button type="submit">Search</button>
        @if(request('search'))
            <a href="{{ route('products.index') }}" class="clear-btn">Clear</a>
        @endif
    </form>

    <div class="button-group">
        <button id="filterLowStock" class="button-fill red-button">Show Low Stock</button>
        <button class="button-fill green-button" onclick="openAddModal()">+ Add Product</button>
    </div>
</div>


<!-- Category Tabs -->
@php
    $isLowStock = request()->has('low_stock') ? ['low_stock' => 1] : [];
@endphp

<div class="filter-tabs">
    <a href="{{ route('products.index', array_merge([], $isLowStock)) }}"
       class="category-tab {{ request('category') ? '' : 'active' }}">All</a>

    <a href="{{ route('products.index', array_merge(['category' => 'medicine'], $isLowStock)) }}"
       class="category-tab {{ request('category') === 'medicine' ? 'active' : '' }}">Medicines</a>

    <a href="{{ route('products.index', array_merge(['category' => 'supplies'], $isLowStock)) }}"
       class="category-tab {{ request('category') === 'supplies' ? 'active' : '' }}">Supplies</a>
</div>




<div id="toast" class="toast">Action completed</div>

<!-- Modals -->
<div id="modalOverlay" class="modal-overlay" onclick="closeModal()"></div>
<div id="modalBox" class="modal-box">
    <div class="modal-header">
        <h2 id="modalTitle">Add Product</h2>
        <span class="close-btn" onclick="closeModal()">&times;</span>
    </div>
    <form id="productForm">
        @csrf
        <input type="hidden" id="formMethod" value="POST">
        <label>Drug Name</label>
        <input type="text" name="name" id="inputName" required>
        <label>Brand</label>
        <input type="text" name="brand" id="inputBrand" required>
        <label>Supplier Price</label>
        <input type="number" name="supplier_price" id="inputSupplierPrice" step="0.01" required>

        <label>Selling Price</label>
        <input type="number" name="selling_price" id="inputPrice" step="0.01" required>
        <label>Stocks</label>
        <input type="number" name="stock" id="inputStock" required>
        <label>Category</label>
        <select name="category" id="inputCategory" required>
            <option value="medicine">Medicine</option>
            <option value="supplies">Supplies</option>
        </select>
        <button type="submit" id="formButton" class="button-fill blue-button">Add</button>
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
let deletedRowData = null;
let currentPage = 1;
let currentCategory = '{{ request('category') ?? 'all' }}';

// ✅ Functions moved to global scope
function openAddModal() {
    document.getElementById('modalTitle').innerText = 'Add New Product';
    document.getElementById('formButton').innerText = 'Add';
    document.getElementById('productForm').action = '/products';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('inputName').value = '';
    document.getElementById('inputBrand').value = '';
    document.getElementById('inputPrice').value = '';
    document.getElementById('inputStock').value = '';
    document.getElementById('inputCategory').value = 'medicine';
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
    .catch(err => {
        console.error(err);
        showToast("Undo failed.", true);
    });
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

// Submit handler
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
        _token: '{{ csrf_token() }}',
        _method: method
    };

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
    })
    .then(res => res.json())
    .then(response => {
        showToast(response.message || 'Saved!');
        closeModal();
        location.reload();
    })
    .catch(error => {
        console.error(error);
        showToast('Failed to save.', true);
    });
});

// Delete handler
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
    .then(async res => {
        if (!res.ok) throw new Error(await res.text());
        return res.json();
    })
    .then(() => location.reload())
    .catch(err => {
        console.error(err);
        showToast("Delete failed.", true);
    });
});

// Category/pagination
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const categoryBtn = e.target.closest('.filter-tabs a');
        if (categoryBtn) {
            e.preventDefault();
            const url = new URL(categoryBtn.href);
            const currentParams = new URLSearchParams(window.location.search);
            if (currentParams.has('low_stock')) {
                url.searchParams.set('low_stock', '1');
            }

            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const container = document.getElementById('productContainer');
                container.innerHTML = html;
                container.classList.remove('slide-up', 'slide-down');
                void container.offsetWidth;
                container.classList.add('slide-up'); // optional animation
            });

            return; // prevent default behavior
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
let lowStockMode = false;
document.getElementById('filterLowStock').addEventListener('click', function () {
    const url = new URL(window.location.href);
    const params = url.searchParams;

    const isLowStockActive = params.get('low_stock') === '1';

    if (isLowStockActive) {
        params.delete('low_stock');
        this.innerText = 'Show Low Stock';
    } else {
        params.set('low_stock', '1');
        this.innerText = 'Show All';
    }

    // Preserve current category filter
    const activeCategory = document.querySelector('.filter-tabs a.active');
    if (activeCategory) {
        const cat = new URL(activeCategory.href).searchParams.get('category');
        if (cat) {
            params.set('category', cat);
        } else {
            params.delete('category');
        }
    }

    fetch(`?${params.toString()}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('productContainer').innerHTML = html;
        history.replaceState(null, '', `?${params.toString()}`);
    });
});


</script>
@endsection
