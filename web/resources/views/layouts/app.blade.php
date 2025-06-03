<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<style>
body {
    font-family: 'Rubik', sans-serif;
    background-color: #fdfaf6;
    margin: 0;
    padding: 0;
}

/* Auth Container */
.auth-container {
    max-width: 420px;
    margin: 60px auto;
    background: #ffffff;
    padding: 32px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

.auth-container h1 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 24px;
    color: #1e293b;
    text-align: center;
}

/* Form Groups */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 6px;
}

.form-input {
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    width: 100%;
    font-size: 15px;
    background-color: #f8fafc;
    transition: border-color 0.3s;
}

.form-input:focus {
    border-color: #8b5e3c;
    outline: none;
}

/* Buttons */
.form-button {
    background-color: #059669;
    color: white;
    padding: 10px;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

.form-button:hover {
    background-color: #047857;
}

/* Footer Link */
.form-footer {
    margin-top: 16px;
    text-align: center;
}

.form-footer a {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
}

.form-footer a:hover {
    text-decoration: underline;
}

/* Toast Messages */
.toast-message {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #4ade80;
    color: #1e293b;
    padding: 14px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    font-family: 'Lexend', sans-serif;
    font-weight: 600;
    z-index: 999;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.4s ease, transform 0.4s ease;
}
.toast-message.show {
    opacity: 1;
    transform: translateY(0);
}



.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border-left: 5px solid #10b981;
}

.alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
    border-left: 5px solid #ef4444;
}

@keyframes fadeOut {
    0%, 90% { opacity: 1; }
    100% { opacity: 0; display: none; }
}


.layout {
    display: flex;
    flex-direction: row; /* make it responsive */
    min-height: 100vh;
    align-items: stretch;
}

.sidebar {
    position: sticky;
    top: 0;
    height: 100vh;
    width: 220px;
    padding: 25px 15px;
    background-color: #eeedec;
    color: #333;
    box-shadow: 4px 0 12px rgba(0, 0, 0, 0.04);
    border-radius: 0 20px 20px 0;
    z-index: 10;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.sidebar h3 {
    font-size: 22px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 40px;
    color: #333;
    letter-spacing: 1px;
}

/* Nav */
.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    margin-bottom: 20px;
}

.sidebar .nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 18px;
    border-radius: 999px;
    font-weight: 500;
    text-decoration: none;
    font-size: 15px;
    position: relative;
    overflow: hidden;
    background-color: transparent;
    color: #333; /* neutral text color */
    transition: all 0.3s ease;
    z-index: 1;
}

/* Shared hover/active fill */
.sidebar .nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 0%;
    z-index: -1;
    transition: width 0.3s ease;
    border-radius: 999px;
    background-color: #8b5e3c; /* unified accent */
}

.sidebar .nav-link:hover::before,
.sidebar .nav-link.active::before {
    width: 100%;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    color: #fff !important;
}


.sidebar-footer {
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.sidebar .contact-info {
    font-size: 14px;
    font-weight: 500;
    color: #7a5d4c;
    text-align: center;
}

.sidebar .sidebar-logo img {
    max-height: 70px;
    margin-bottom: 10px;
}

/* Hamburger & Overlay */
.hamburger {
    width: auto;
    display: none;
    position: fixed;
    top: 8px;
    left: 20px;
    font-size: 26px;
    background: transparent;
    border: none;
    color: white;
    padding: 10px 14px;
    border-radius: 50%;
    z-index: 1001;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.hamburger:hover {
    transform: scale(1.1);
}

.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1002;
}

.overlay.show {
    display: block;
}

/* Main content area */
.content {
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
    overflow-x: hidden;
}


/* Section Cards */
.page-section {
    background: #ffffff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
    margin-bottom: 30px;
}

.page-section h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    padding-left: 10px;
    border-left: 4px solid #8b5e3c;
    color: #8b5e3c;
}


/* Mobile */
@media (max-width: 768px) {
    .layout {
        flex-direction: column; /* stack sidebar and content */
    }
    .sidebar {
        position: fixed;
        top: 0;
        left: -320px;
        height: 100%;
         background-color: #eeedec;
        color: #333;
        transition: left 0.3s ease;
        z-index: 1003;
    }

    .sidebar.open {
        left: 0;
    }

    .hamburger {
        display: block;
    }

    .content {
        margin-left: 0;
        padding-top: 60px;
    }
}
.top-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #ffffff;
    padding: 16px 24px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    margin-bottom: 24px;
}

.top-header .app-name {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    font-size: 18px;
    color: #1e293b;
    text-decoration: none;
}

.top-header .app-name .logo {
    height: 36px;
    width: 36px;
    border-radius: 50%;
    object-fit: cover;
}

.top-header .right {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
}


.top-header .right .user-name {
    font-weight: 500;
    color: #333;
}

.top-header .right .profile-img img {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ccc;
}

.top-header .dropdown:hover .dropdown-menu {
    display: block;
}

.top-header .profile-dropdown {
    background-color: #eeedec;
    padding: 10px 16px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 500;
    color: #1e293b;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    transition: background 0.3s ease;
}

.top-header .profile-dropdown:hover {
    background-color: #dfdfdf;
}

.dropdown-menu {
    position: absolute;
    right: 24px;
    top: 80px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    padding: 12px;
    display: none;
    flex-direction: column;
    min-width: 180px;
    z-index: 10;
}

.dropdown-menu .dropdown-item,
.dropdown-menu button {
    background: none;
    border: none;
    text-align: left;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 500;
    color: #1e293b;
    width: 100%;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.dropdown-menu .dropdown-item:hover,
.dropdown-menu button:hover {
    background-color: #f3f4f6;
}
</style>

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
                <li>
                    <a href="{{ route('profile.custom') }}" class="nav-link {{ request()->routeIs('profile.custom') ? 'active' : '' }}">
                        Profile
                    </a>
                </li>
                @auth
                    @if(Auth::user()->is_admin)
                        <li>
                            <a href="{{ route('admin.accounts') }}" class="nav-link {{ request()->routeIs('admin.accounts') ? 'active' : '' }}">
                                Accounts
                            </a>
                        </li>
                    @endif
                @endauth
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
            <div class="top-header">
                <div class="left">
                    <a href="{{ route('dashboard') }}" class="app-name">
                        <img src="{{ asset('images/lalas-logo.jpg') }}" alt="Logo" class="logo">
                        <span>PD LALAS Pharmacy</span>
                    </a>
                </div>
                <div class="right">
                    <div class="profile-dropdown" onclick="toggleDropdown()">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                    </div>
                    <div id="dropdownMenu" class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('profile.custom') }}">Edit Profile</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

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
<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdownMenu');
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    }

    // Optional: close on outside click
    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('dropdownMenu');
        const trigger = document.querySelector('.profile-dropdown');
        if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
</script>

</body>

</html>
