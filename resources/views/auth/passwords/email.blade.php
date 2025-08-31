@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg flex flex-col md:flex-row overflow-hidden w-full max-w-4xl" data-aos="zoom-in">

        {{-- Left side: Form --}}
        <div class="md:w-1/2 p-8" data-aos="fade-right">
            <h2 class="text-2xl font-bold mb-4">Lupa Kata Sandi</h2>
            <p class="mb-6 text-gray-600">
                Masukkan nomor WhatsApp Anda untuk menerima kode OTP.
            </p>

            @if (session('status'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <input type="text" name="whatsapp_number" placeholder="Nomor WhatsApp" required
                    class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">

                @error('whatsapp_number')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

                <button type="submit"
                    class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700 transition">
                    Kirim Kode OTP
                </button>
            </form>
        </div>

        {{-- Right side: Illustration --}}
        <div class="md:w-1/2 bg-blue-50 flex justify-center items-center p-6" data-aos="fade-left">
            <div class="text-center">
                <img src="{{ asset('forget.jpg') }}"
                     alt="Forgot Password" class="max-w-sm mx-auto">
                <h3 class="mt-4 font-bold text-lg">Lupa Kata Sandi?</h3>
                <p class="text-gray-500">Kami akan mengirim kode OTP ke WhatsApp Anda.</p>
            </div>
        </div>
    </div>
</div>
@endsection
