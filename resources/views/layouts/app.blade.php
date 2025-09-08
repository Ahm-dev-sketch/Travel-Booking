<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>PT. PELITA TRANSPORT</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 m-0 p-0">

    @if (session('success'))
        <div data-success-message="{{ session('success') }}" style="display: none;"></div>
    @endif

    @if (session('error'))
        <div data-error-message="{{ session('error') }}" style="display: none;"></div>
    @endif

    <nav class="bg-blue-900 text-white shadow-md relative">
        <div class="container mx-auto flex justify-between items-center px-4 py-3 md:px-6">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-2 text-lg md:text-xl font-bold">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 rounded-full object-cover">
                <span>PT. PELITA TRANSPORT</span>
            </a>

            {{-- Hamburger mobile --}}
            <button id="menu-toggle" class="md:hidden focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            {{-- Desktop menu --}}
            <div class="hidden md:flex md:items-center gap-6">
                <a href="{{ route('home') }}" class="relative group">
                    Home
                    <span
                        class="absolute left-0 -bottom-1 w-0 h-[2px] bg-white transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ route('jadwal') }}" class="relative group">
                    Melihat Jadwal
                    <span
                        class="absolute left-0 -bottom-1 w-0 h-[2px] bg-white transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ auth()->check() ? route('pesan') : route('login') }}" class="relative group">
                    Pesan Tiket
                    <span
                        class="absolute left-0 -bottom-1 w-0 h-[2px] bg-white transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ auth()->check() ? route('riwayat') : route('login') }}" class="relative group">
                    Riwayat Transaksi
                    <span
                        class="absolute left-0 -bottom-1 w-0 h-[2px] bg-white transition-all group-hover:w-full"></span>
                </a>

                @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-500 rounded hover:bg-blue-600 transition">
                        Login
                    </a>
                @else
                    @php
                        $firstName = \Illuminate\Support\Str::before(auth()->user()->name, ' ');
                    @endphp

                    <div class="relative flex items-center gap-2">
                        <span id="greeting">Selamat Malam</span>,
                        <strong>{{ $firstName }}</strong>

                        {{-- Icon dropdown --}}
                        <span id="greeting-icon" class="ml-1 cursor-pointer">🌙</span>

                        {{-- Dropdown --}}
                        <div id="user-dropdown"
                            class="hidden absolute right-0 top-10 mt-1 w-40 bg-white text-gray-700 rounded shadow-lg py-2 z-50 transition-opacity duration-200 opacity-0">
                            <button type="button" id="logout-btn" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                Logout
                            </button>
                        </div>

                        {{-- Hidden form --}}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                @endguest
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="menu" class="hidden flex-col bg-blue-800 md:hidden px-6 py-4 space-y-2">
            <a href="{{ route('home') }}" class="block hover:text-blue-300">Home</a>
            <a href="{{ route('jadwal') }}" class="block hover:text-blue-300">Jadwal Keberangkatan</a>
            <a href="{{ auth()->check() ? route('pesan') : route('login') }}" class="block hover:text-blue-300">Pesan
                Tiket</a>
            <a href="{{ auth()->check() ? route('riwayat') : route('login') }}"
                class="block hover:text-blue-300">Riwayat Transaksi</a>

            @guest
                <a href="{{ route('login') }}" class="block px-4 py-2 bg-blue-500 rounded hover:bg-blue-600 transition">
                    Login
                </a>
            @else
                <span class="block">Halo, {{ auth()->user()->name }}</span>
                <button type="button" id="logout-btn-mobile"
                    class="w-full px-4 py-2 bg-red-500 rounded hover:bg-red-600 transition">
                    Logout
                </button>
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endguest
        </div>
    </nav>

    <main class="min-h-screen">
        <div class="container mx-auto p-6">
            @yield('content')
        </div>
    </main>

    @hasSection('footer')
        @yield('footer')
    @endif

    @stack('scripts')
</body>

</html>
