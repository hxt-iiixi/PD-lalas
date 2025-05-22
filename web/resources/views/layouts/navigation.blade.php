<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <!-- Left: App name -->
    <div class="text-xl font-bold text-gray-800">
        <a href="{{ route('dashboard') }}">🧪 PharmacyApp</a>
    </div>

    <!-- Right: User menu -->
    @auth
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-700">Hello, {{ Auth::user()->name }}</span>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="text-white bg-red-500 hover:bg-red-600 px-4 py-1 rounded text-sm"
                >
                    Logout
                </button>
            </form>
        </div>
    @endauth

    @guest
        <div class="text-sm">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> |
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
        </div>
    @endguest
</nav>
