@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg flex flex-col md:flex-row overflow-hidden w-full max-w-4xl" data-aos="zoom-in">

        {{-- Left side: Form --}}
        <div class="md:w-1/2 p-8" data-aos="fade-right">
            <h2 class="text-2xl font-bold mb-4">Verifikasi OTP</h2>
            <p class="mb-6">Kode OTP telah dikirim ke WhatsApp: <strong>{{ $whatsapp_number }}</strong></p>

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

            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-600 rounded text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.verify.submit') }}" class="space-y-4">
                @csrf

                {{-- OTP Code --}}
                <div>
                    <input type="text" name="otp_code" placeholder="Masukkan 6 digit kode OTP" required maxlength="6"
                        class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300 text-center text-xl tracking-widest"
                        autocomplete="off" autofocus>
                    @error('otp_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-green-600 text-white p-3 rounded hover:bg-green-700 transition">
                    Verifikasi & Daftar
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Tidak menerima kode OTP?</p>
                <form method="POST" action="{{ route('register') }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="name" value="{{ old('name') }}">
                    <input type="hidden" name="email" value="{{ old('email') }}">
                    <input type="hidden" name="whatsapp_number" value="{{ old('whatsapp_number') }}">
                    <input type="hidden" name="password" value="{{ old('password') }}">
                    <input type="hidden" name="password_confirmation" value="{{ old('password_confirmation') }}">
                    <button type="submit" class="text-blue-500 hover:underline text-sm">
                        Kirim ulang OTP
                    </button>
                </form>
            </div>
        </div>

        {{-- Right side: Illustration --}}
        <div class="md:w-1/2 bg-green-50 flex justify-center items-center p-6" data-aos="fade-left">
            <div class="text-center">
                <img src="https://img.freepik.com/free-vector/otp-security-concept-illustration_114360-7896.jpg"
                     alt="OTP Verification" class="max-w-sm mx-auto">
                <h3 class="mt-4 font-bold text-lg">Verifikasi Keamanan</h3>
                <p class="text-gray-500">Masukkan kode OTP yang dikirim ke WhatsApp Anda</p>
            </div>
        </div>
    </div>
</div>
@endsection
