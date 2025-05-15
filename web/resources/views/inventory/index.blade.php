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
    <button onclick="openAddModal()">+ Add Product</button>
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
        <label>Selling Price</label>
        <input type="number" name="selling_price" id="inputPrice" step="0.01" required>
        <label>Stocks</label>
        <input type="number" name="stock" id="inputStock" required>
        <button type="submit" id="formButton">Add</button>
    </form>
</div>

<div id="deleteOverlay" class="modal-overlay" onclick="closeDeleteModal()"></div>
<div id="deleteModal" class="modal-box">
    <div class="modal-header">
        <h2>Confirm Deletion</h2>
        <span class="close-btn" onclick="closeDeleteModal()">&times;</span>
    </div>
    <p id="deleteMessage">Are you sure?</p>
    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
        <button onclick="closeDeleteModal()">Cancel</button>
        <button id="confirmDeleteBtn" style="background-color: red;">Yes, Delete</button>
    </div>
</div>

<div id="productContainer">
    @include('inventory.partials.table')
</div>

<script>
let deleteTargetId = null;
let deletedRowData = null;
let currentPage = 1;

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

        const tableBody = document.querySelector('table tbody');
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-id', response.product.id);
        newRow.innerHTML = `
            <td>${response.product.name}</td>
            <td>${response.product.brand}</td>
            <td>${response.product.selling_price}</td>
            <td>${response.product.stock}</td>
            <td>
                <div class="action-buttons">
                    <button onclick='openEditModal(${JSON.stringify(response.product)})'>Edit</button>
                    <button onclick='triggerDelete(${response.product.id}, "${response.product.name}")'>Delete</button>
                </div>
            </td>
        `;
        newRow.classList.add('row-fade-in');
        tableBody.prepend(newRow);
    })
    .catch(error => {
        console.error(error);
        showToast('Failed to save.', true);
    });
});

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

document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
    if (!deleteTargetId) return;

    const row = document.querySelector(`tr[data-id="${deleteTargetId}"]`);
    deletedRowData = row ? row.cloneNode(true) : null;

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
    .then(data => {
        showToast(data.message || "Deleted.", false, deleteTargetId);
        closeDeleteModal();

        const row = document.querySelector(`tr[data-id="${deleteTargetId}"]`);
        if (row) {
            row.remove();

            const rowsLeft = document.querySelectorAll('table tbody tr[data-id]').length;
            if (rowsLeft === 0) {
                document.querySelector('table tbody').innerHTML = '<tr><td colspan="5">No products found.</td></tr>';
            }
        }

        deleteTargetId = null;
    })
    .catch(err => {
        console.error(err);
        showToast("Delete failed.", true);
    });
});

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
        if (deletedRowData) {
            const tableBody = document.querySelector('table tbody');
            tableBody.prepend(deletedRowData);
            deletedRowData = null;
        }
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

document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const target = e.target.closest('.pagination a');
        if (target) {
            e.preventDefault();
            const link = target.href;
            const page = new URL(link).searchParams.get('page');
            const direction = parseInt(page) > currentPage ? 'slide-left' : 'slide-right';

            const container = document.getElementById('productContainer');

            fetch(link, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                container.innerHTML = html;
                container.classList.remove('slide-left', 'slide-right', 'fade');
                void container.offsetWidth;
                container.classList.add(direction);
                currentPage = parseInt(page);
            });
        }
    });
});
</script>
@endsection
