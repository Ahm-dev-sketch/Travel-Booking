@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4" data-aos="fade-down">Pemesanan Tiket</h2>

    {{-- Alert sukses pakai SweetAlert --}}
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: "{{ session('success') }}",
                    confirmButtonText: "OK",
                    showConfirmButton: true
                });
            });
        </script>
    @endif

    <form id="booking-form" action="{{ route('booking.store') }}" method="POST">
        @csrf

        {{-- Pilih Jadwal --}}
        <label class="block mb-4" data-aos="fade-right">
            Pilih Jadwal:
            <select name="jadwal_id" id="jadwal_id" class="w-full border p-2 rounded" required>
                <option value="">-- Pilih Jadwal --</option>
                @foreach ($jadwals as $jadwal)
                    <option value="{{ $jadwal->id }}"
                        @if(isset($jadwal_id) && $jadwal_id == $jadwal->id) selected @endif>
                        {{ $jadwal->tujuan }} - {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                        {{ $jadwal->jam }}
                    </option>
                @endforeach
            </select>
        </label>

        {{-- Layout Kursi --}}
        <div id="seat-layout" class="grid grid-cols-4 gap-4 max-w-md" data-aos="zoom-in">
            @php
                $seats = ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'B3'];
            @endphp
            @foreach ($seats as $seat)
                <label class="cursor-pointer">
                    <input type="checkbox" name="seats[]" value="{{ $seat }}" class="hidden seat-checkbox" disabled>
                    <div class="w-16 h-16 flex items-center justify-center rounded bg-gray-300 text-black">
                        {{ $seat }}
                    </div>
                </label>
            @endforeach
        </div>

        {{-- Tombol Pesan --}}
        <div class="mt-6" data-aos="fade-up">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Pesan
            </button>
        </div>
    </form>
@endsection
