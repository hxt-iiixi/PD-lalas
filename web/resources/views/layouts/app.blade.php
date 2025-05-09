<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2 class="logo">💊 Pharmacy</h2>
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('products.index') }}">Products</a></li>
            </ul>
        </aside>

        <!-- Page Content -->
        <main class="content">
            @yield('content')
        </main>
    </div>
</body>
</html>
