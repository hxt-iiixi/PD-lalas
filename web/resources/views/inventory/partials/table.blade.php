
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Drug Name</th>
                <th>Brand</th>
                <th>Supplier Price</th>
                <th>Selling Price</th>
                <th>Stocks</th>
                <th>Actions</th>
                <th>Expiry Date</th> <!-- ✅ Now placed last -->
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
            <tr data-id="{{ $product->id }}">
                <td>{{ $product->name }}</td>
                <td>{{ $product->brand }}</td>
                <td>{{ $product->supplier_price }}</td>
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
            @php
                    $expiry = \Carbon\Carbon::parse($product->expiry_date);
                    $today = \Carbon\Carbon::now()->startOfDay();
                    $daysDiff = $today->diffInDays($expiry, false);
                    $isToday = $expiry->isSameDay($today);
                @endphp

                <td style="
                {{ $isToday ? 'background-color:#ff0810;color:#000000;font-weight:bold;' : 
                    ($today >= $expiry->copy()->subMonths(6) && $today < $expiry ? 'background-color:#FF5F15;color:#000000;font-weight:bold;' : '') }}
                ">
                {{ $expiry->format('Y-m-d') }}
                </td>

            </tr>
            @empty
            <tr><td colspan="7">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center mt-4">
    {{ $products->withQueryString()->links() }}
</div>
