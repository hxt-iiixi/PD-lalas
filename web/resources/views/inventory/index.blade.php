<!-- resources/views/inventory/index.blade.php -->
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

    <button onclick="openModal()">+ Add Product</button>
</div>

@if(session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

<!-- Modal Overlay -->
<div id="modalOverlay" class="modal-overlay" onclick="closeModal()"></div>

<!-- Modal Box -->
<div id="modalBox" class="modal-box">
    <div class="modal-header">
        <h2>Add New Product</h2>
        <span class="close-btn" onclick="closeModal()">&times;</span>
    </div>
    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <label>Drug Name</label>
        <input type="text" name="name" required>

        <label>Brand</label>
        <input type="text" name="brand" required>

        <label>Selling Price</label>
        <input type="number" step="0.01" name="selling_price" required>

        <label>Stocks</label>
        <input type="number" name="stock" required>

        <button type="submit">Add</button>
    </form>
</div>

<table border="1" cellpadding="10" cellspacing="0">
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
                <a href="{{ route('products.edit', $product->id) }}">Edit</a> |
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5">No products found.</td></tr>
        @endforelse
    </tbody>
</table>

<script>
    function openModal() {
        const modal = document.getElementById('modalBox');
        const overlay = document.getElementById('modalOverlay');
        modal.style.display = 'block';
        overlay.style.display = 'block';
        modal.classList.remove('hide');
        modal.classList.add('show');
    }

    function closeModal() {
        const modal = document.getElementById('modalBox');
        const overlay = document.getElementById('modalOverlay');
        modal.classList.remove('show');
        modal.classList.add('hide');
        overlay.style.display = 'none';

        setTimeout(() => {
            modal.style.display = 'none';
            modal.classList.remove('hide');
        }, 300);
    }

    // Click outside modal content to close
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('modalBox');
        const overlay = document.getElementById('modalOverlay');

        if (e.target === overlay) {
            closeModal();
        }
    });
</script>


@endsection
