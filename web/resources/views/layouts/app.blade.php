<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lexend" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <!-- Hamburger Button (move outside layout for proper absolute positioning) -->
<button id="hamburgerBtn" class="hamburger">&#9776;</button>

    <div class="layout">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <h3>My Pharmacy</h3>
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('products.index') }}">Inventory</a></li>
            </ul>
        </div>
        <!-- Overlay -->
        <div id="sidebarOverlay" class="overlay" onclick="closeSidebar()"></div>

        <!-- Main Content -->
        <main class="content">
            @yield('content')
        </main>
    </div>


   <script>
    const hamburger = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }

    hamburger.addEventListener('click', openSidebar);
</script>

</body>
</html>
