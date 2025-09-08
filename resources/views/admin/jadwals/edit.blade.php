@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6 flex items-center gap-2" data-aos="fade-down">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19V6a2 2 0 012-2h2m4 0h2a2 2 0 012 2v13a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2h4" />
    </svg>
    Edit Jadwal
</h1>

<div class="bg-white p-6 rounded shadow" data-aos="fade-up">

    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: "{{ session('success') }}",
                    confirmButtonText: "OK",
                    showConfirmButton: true,
                    timer: null
                });
            });
        </script>
    @endif

    <form action="{{ route('admin.jadwals.update', $jadwal) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="rute_id" class="block text-sm font-medium text-gray-700 mb-2">Rute</label>
                <select id="rute_id" name="rute_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled>Pilih Rute</option>
                    @foreach($rutes as $rute)
                        <option value="{{ $rute->id }}" {{ old('rute_id', $jadwal->rute_id) == $rute->id ? 'selected' : '' }}>
                            {{ $rute->kota_asal }} - {{ $rute->kota_tujuan }}
                        </option>
                    @endforeach
                </select>
                @error('rute_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $jadwal->tanggal) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('tanggal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="jam" class="block text-sm font-medium text-gray-700 mb-2">Jam</label>
                <input type="time" name="jam" id="jam" value="{{ old('jam', $jadwal->jam) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('jam')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                <input type="number" name="harga" id="harga" value="{{ old('harga', (int) $jadwal->harga) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('harga')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                Update Jadwal
            </button>
            <a href="{{ route('admin.jadwals') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
