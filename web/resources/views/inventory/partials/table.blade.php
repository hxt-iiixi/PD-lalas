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
        <tr data-id="{{ $product->id }}">
            <td>{{ $product->name }}</td>
            <td>{{ $product->brand }}</td>
            <td>{{ $product->selling_price }}</td>
            <td>{{ $product->stock }}</td>
            <td>
                <div class="action-buttons">
                    <button onclick='openEditModal(@json($product))'>Edit</button>
                    <button onclick='triggerDelete({{ $product->id }}, "{{ $product->name }}")'>Delete</button>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="5">No products found.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-4">
    {{ $products->withQueryString()->links() }}
</div>
