<table>
    <thead>
        <tr>
            <th>Drug Name</th>
            <th>Brand</th>
            <th>Supplier Price</th> <!-- new -->
            <th>Selling Price</th>
            <th>Stocks</th>
            <th>Actions</th>
        </tr>
    </thead> <!-- ✅ Missing this closing tag -->
    <tbody>
        @forelse ($products as $product)
        <tr data-id="{{ $product->id }}">
            <td>{{ $product->name }}</td>
            <td>{{ $product->brand }}</td>
            <td>{{ $product->supplier_price }}</td> <!-- new -->
            <td>{{ $product->selling_price }}</td>
        <td class="{{ $product->stock < 21 ? 'low-stock-red' : ($product->stock < 50 ? 'low-stock-orange' : '') }}">
            {{ $product->stock }}
        </td>
   


            <td>
                <div class="action-buttons">
                    <button class="button-fill blue-button" onclick='openEditModal(@json($product))'>Edit</button>
                    <button class="button-fill red-button" onclick='triggerDelete({{ $product->id }}, "{{ $product->name }}")'>Delete</button>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6">No products found.</td></tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-center mt-4">
    {{ $products->withQueryString()->links() }}
</div>
