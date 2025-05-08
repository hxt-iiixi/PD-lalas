<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>

    <form method="POST" action="{{ route('products.update', $product->id) }}">
        @csrf
        @method('PUT')
        <label>Name</label><br>
        <input type="text" name="name" value="{{ $product->name }}" required><br><br>

        <label>Stock</label><br>
        <input type="number" name="stock" value="{{ $product->stock }}" required><br><br>

        <label>Category</label><br>
        <input type="text" name="category" value="{{ $product->category }}" required><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('products.index') }}">Back</a>
</body>
</html>
