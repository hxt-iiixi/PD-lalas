
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        <label>Name</label><br>
        <input type="text" name="name" required><br><br>

        <label>Stock</label><br>
        <input type="number" name="stock" required><br><br>

        <label>Category</label><br>
        <input type="text" name="category" required><br><br>

        <button type="submit">Add</button>
    </form>

    <br>
    <a href="{{ route('products.index') }}">Back</a>
</body>
</html>
