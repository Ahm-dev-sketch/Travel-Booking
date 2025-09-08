<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - PT. PELITA TRANSPORT</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <!-- Hamburger Button -->
        <button id="menu-toggle" class="md:hidden p-4 focus:outline-none" aria-label="Toggle Sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>

        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-64 h-screen bg-blue-900 text-white flex flex-col transform -translate-x-full
                   md:translate-x-0 transition-transform duration-300 md:static fixed z-50">
            <div class="p-4 text-xl font-bold border-b border-blue-700 flex items-center gap-2">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 rounded-full object-cover">
                PT. PELITA TRANSPORT
            </div>

            <nav class="flex-1 p-4 space-y-2">
                {{-- contoh link --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                          {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800' : '' }}">
                    <i class="fa fa-home"></i> Dashboard
                </a>
                <a href="{{ route('admin.bookings') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                          {{ request()->routeIs('admin.bookings') ? 'bg-blue-800' : '' }}">
                    <i class="fa fa-ticket"></i> Data Pemesanan
                </a>
                <a href="{{ route('admin.jadwals') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                          {{ request()->routeIs('admin.jadwals') ? 'bg-blue-800' : '' }}">
                    <i class="fa fa-calendar"></i> Penjadwalan
                </a>
                <a href="{{ route('admin.pelanggan') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                          {{ request()->routeIs('admin.pelanggan') ? 'bg-blue-800' : '' }}">
                    <i class="fa fa-users"></i> Data Pelanggan
                </a>
                <a href="{{ route('admin.laporan') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                          {{ request()->routeIs('admin.laporan') ? 'bg-blue-800' : '' }}">
                    <i class="fa fa-file-invoice-dollar"></i> Laporan Pendapatan
                </a>
                <a href="{{ route('admin.rute') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                          {{ request()->routeIs('admin.rute') ? 'bg-blue-800' : '' }}">
                    <i class="fa fa-road"></i> Data Rute
                </a>
                <a href="{{ route('admin.mobil') }}"
                    class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                          {{ request()->routeIs('admin.mobil') ? 'bg-blue-800' : '' }}">
                    <i class="fa fa-bus"></i> Data Mobil
                </a>
            </nav>

            <div class="p-4 border-t border-blue-700">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button id="logout-btn" type="button"
                        class="w-full flex items-center justify-center gap-2 bg-red-600 py-2 rounded hover:bg-red-700">
                        <i class="fa fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</body>

</html>
