@extends('layouts.app')
@section('title', 'Products')
@section('content')
<link rel="stylesheet" href="{{ asset('css/products.css') }}">

<div class="top-bar">
    <form method="GET" action="{{ route('products.index') }}" class="search-form">
        <input type="text" name="search" placeholder="Search drug name or brand..." value="{{ request('search') }}">
        <button type="submit">Search</button>
        @if(request('search'))
            <a href="{{ route('products.index') }}" class="clear-btn">Clear</a>
        @endif
    </form>
    <button onclick="openAddModal()">+ Add Product</button>
</div>

<!-- Toast -->
<div id="toast" class="toast">Action completed</div>

<!-- Overlay -->
<div id="modalOverlay" class="modal-overlay" onclick="closeModal()"></div>

<!-- Add/Edit Modal -->
<div id="modalBox" class="modal-box">
    <div class="modal-header">
        <h2 id="modalTitle">Add New Product</h2>
        <span class="close-btn" onclick="closeModal()">&times;</span>
    </div>
    <form id="productForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="formMethod" value="POST">

        <label>Drug Name</label>
        <input type="text" name="name" id="inputName" required>

        <label>Brand</label>
        <input type="text" name="brand" id="inputBrand" required>

        <label>Selling Price</label>
        <input type="number" step="0.01" name="selling_price" id="inputPrice" required>

        <label>Stocks</label>
        <input type="number" name="stock" id="inputStock" required>

        <button type="submit" id="formButton">Add</button>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteOverlay" class="modal-overlay" onclick="closeDeleteModal()"></div>
<div id="deleteModal" class="modal-box">
    <div class="modal-header">
        <h2>Confirm Deletion</h2>
        <span class="close-btn" onclick="closeDeleteModal()">&times;</span>
    </div>
    <p>Are you sure you want to delete this product?</p>
    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
        <button onclick="closeDeleteModal()">Cancel</button>
        <button id="confirmDeleteBtn">Yes, Delete</button>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Drug Name</th>
            <th>Brand</th>
            <th>Selling Price</th>
            <th>Stocks</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->brand }}</td>
            <td>{{ $product->selling_price }}</td>
            <td>{{ $product->stock }}</td>
            <td>
                <button onclick="openEditModal({{ $product }})">Edit</button>
                <button onclick="triggerDelete({{ $product->id }})">Delete</button>
            </td>
        </tr>
        @empty
        <tr><td colspan="5">No products found.</td></tr>
        @endforelse
    </tbody>
</table>

<script>
    let deleteTargetId = null;

    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Add New Product';
        document.getElementById('formButton').innerText = 'Add';
        document.getElementById('productForm').action = '/products';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('inputName').value = '';
        document.getElementById('inputBrand').value = '';
        document.getElementById('inputPrice').value = '';
        document.getElementById('inputStock').value = '';
        showModal();
    }

    function openEditModal(product) {
        document.getElementById('modalTitle').innerText = 'Edit Product';
        document.getElementById('formButton').innerText = 'Update';
        document.getElementById('productForm').action = `/products/${product.id}`;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('inputName').value = product.name;
        document.getElementById('inputBrand').value = product.brand;
        document.getElementById('inputPrice').value = product.selling_price;
        document.getElementById('inputStock').value = product.stock;
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

    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const url = form.action;
        const method = document.getElementById('formMethod').value;

        const formData = {
            name: document.getElementById('inputName').value,
            brand: document.getElementById('inputBrand').value,
            selling_price: document.getElementById('inputPrice').value,
            stock: document.getElementById('inputStock').value,
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
            setTimeout(() => location.reload(), 1000);
        })
        .catch(error => {
            console.error(error);
            showToast('Failed to save.', true);
        });
    });

    function triggerDelete(id) {
        deleteTargetId = id;
        document.getElementById('deleteModal').classList.add('show');
        document.getElementById('deleteOverlay').classList.add('show');
    }

    function closeDeleteModal() {
        deleteTargetId = null;
        document.getElementById('deleteModal').classList.remove('show');
        document.getElementById('deleteOverlay').classList.remove('show');
    }

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
        .then(res => res.json())
        .then(data => {
            showToast(data.message || "Deleted.");
            closeDeleteModal();
            setTimeout(() => location.reload(), 1000);
        })
        .catch(err => {
            console.error(err);
            showToast("Delete failed.", true);
        });
    });

    function showToast(message, isError = false) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.background = isError ? '#dc3545' : '#28a745';
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2000);
    }
</script>
@endsection
