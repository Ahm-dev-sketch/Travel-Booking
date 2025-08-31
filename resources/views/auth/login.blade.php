@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg flex flex-col md:flex-row overflow-hidden w-full max-w-4xl" data-aos="zoom-in">

        {{-- Left side: Form --}}
        <div class="md:w-1/2 p-8" data-aos="fade-right">
            <h2 class="text-2xl font-bold mb-4">Masuk Akun</h2>
            <p class="mb-6">Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Daftar di sini</a>
            </p>

            {{-- pesan error login --}}
            @if ($errors->has('email'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-600 rounded">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required
                    class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">

                {{-- Password --}}
                <input type="password" name="password" placeholder="Kata Sandi" required
                    class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">

                <button type="submit"
                    class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700 transition">
                    Login
                </button>
            </form>

            {{-- Forgot Password Link --}}
            <div class="mt-4 text-center">
                <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">
                    Lupa kata sandi?
                </a>
            </div>
        </div>

        {{-- Right side: Illustration --}}
        <div class="md:w-1/2 bg-blue-50 flex justify-center items-center p-6" data-aos="fade-left">
            <div class="text-center">
                <img src="{{ asset('login.jpg') }}"
                     alt="Travel" class="max-w-sm mx-auto">
                <h3 class="mt-4 font-bold text-lg">PT. Pelita Transport </h3>
                <p class="text-gray-500">Travel Terbaik Untuk Anda </p>
            </div>
        </div>
    </div>
</div>
@endsection
