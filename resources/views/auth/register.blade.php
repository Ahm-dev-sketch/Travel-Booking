@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg flex flex-col md:flex-row overflow-hidden w-full max-w-4xl" data-aos="zoom-in">

        {{-- Left side: Form --}}
        <div class="md:w-1/2 p-8" data-aos="fade-right">
            <h2 class="text-2xl font-bold mb-4">Daftar Akun</h2>
            <p class="mb-6">Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login di sini</a>
            </p>

            {{-- tampilkan semua error sekaligus --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-600 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Name --}}
                <div>
                    <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required
                        class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required
                        class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- WhatsApp Number --}}
                <div>
                    <input type="tel" name="whatsapp_number" placeholder="Nomor WA aktif (contoh: 08123456789)" value="{{ old('whatsapp_number') }}" required
                        class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">
                    @error('whatsapp_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <input type="password" name="password" placeholder="Kata Sandi" required
                        class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" required
                        class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">
                </div>

                <button type="submit"
                    class="w-full bg-green-600 text-white p-3 rounded hover:bg-green-700 transition">
                    Register
                </button>
            </form>
        </div>

        {{-- Right side: Illustration --}}
        <div class="md:w-1/2 bg-green-50 flex justify-center items-center p-6" data-aos="fade-left">
            <div class="text-center">
                <img src="{{ asset('register.jpg') }}"
                     alt="Travel" class="max-w-sm mx-auto">
                <h3 class="mt-4 font-bold text-lg">PT. Pelita Transport </h3>
                <p class="text-gray-500">Travel Terbaik Untuk Anda </p>
            </div>
        </div>
    </div>
</div>
@endsection
