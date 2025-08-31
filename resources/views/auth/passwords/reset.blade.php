@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg flex flex-col md:flex-row overflow-hidden w-full max-w-4xl" data-aos="zoom-in">

        {{-- Left side: Form --}}
        <div class="md:w-1/2 p-8" data-aos="fade-right">
            <h2 class="text-2xl font-bold mb-4">Reset Kata Sandi</h2>
            <p class="mb-6 text-gray-600">Masukkan kode OTP dan kata sandi baru untuk akun Anda.</p>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="whatsapp_number" value="{{ $whatsapp_number ?? old('whatsapp_number') }}">

                <input type="text" name="whatsapp_number" value="{{ $whatsapp_number ?? old('whatsapp_number') }}" required
                    class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300" placeholder="Nomor WhatsApp" readonly>

                <input type="text" name="otp_code" placeholder="Kode OTP" required
                    class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">

                <input type="password" name="password" placeholder="Kata Sandi Baru" required
                    class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">

                <input type="password" name="password_confirmation" placeholder="Konfirmasi Kata Sandi Baru" required
                    class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">

                @error('whatsapp_number')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                @error('otp_code')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

                <button type="submit"
                    class="w-full bg-green-600 text-white p-3 rounded hover:bg-green-700 transition">
                    Reset Password
                </button>
            </form>
        </div>

        {{-- Right side: Illustration --}}
        <div class="md:w-1/2 bg-green-50 flex justify-center items-center p-6" data-aos="fade-left">
            <div class="text-center">
                 <img src="{{ asset('reset.jpg') }}"
                     alt="Reset Password" class="max-w-sm mx-auto">
                <h3 class="mt-4 font-bold text-lg">Atur Ulang Kata Sandi</h3>
                <p class="text-gray-500">Gunakan kata sandi yang kuat dan aman.</p>
            </div>
        </div>
    </div>
</div>
@endsection
