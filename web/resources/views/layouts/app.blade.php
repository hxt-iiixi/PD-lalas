<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Lexend" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <!-- Hamburger Button (move outside layout for proper absolute positioning) -->
<button id="hamburgerBtn" class="hamburger">&#9776;</button>

    <div class="layout">
        <!-- Sidebar -->
       <div id="sidebar" class="sidebar d-flex flex-column justify-content-between">
        <!-- Top Logo & Menu -->
        <div>
            <div class="sidebar-logo text-center mb-4">
                <img src="{{ asset('images/lalas-logo.jpg') }}" alt="Lalas Pharmacy Logo" class="img-fluid" style="max-height: 80px;">
            </div>
            <ul class="nav flex-column">
                <li><a href="{{ route('dashboard') }}" class="nav-link dashboard {{ request()->routeIs('dashboard') ? 'active' : '' }}"> Dashboard</a></li>
                <li><a href="{{ route('products.index') }}" class="nav-link inventory {{ request()->routeIs('products.index') ? 'active' : '' }}"> Inventory</a></li>
                <li><a href="{{ route('inventory.history') }}" class="nav-link sales {{ request()->routeIs('inventory.history') ? 'active' : '' }}"> Sales History</a></li>
                <li><a href="#" class="nav-link profile"> Profile</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link logout btn btn-link p-0 m-0 text-start w-100"> Logout</button>
                    </form>
                </li>
            </ul>

        </div>
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
