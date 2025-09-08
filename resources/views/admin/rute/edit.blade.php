@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Edit Rute
    </h1>

    <div class="bg-white p-6 rounded shadow max-w-lg">
        <form action="{{ route('admin.rute.update', $rute) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Kota Asal --}}
            <div>
                <label for="kota_asal" class="block text-sm font-medium text-gray-700">Kota Asal</label>
                <input type="text" id="kota_asal" name="kota_asal" value="{{ old('kota_asal', $rute->kota_asal) }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('kota_asal')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kota Tujuan --}}
            <div>
                <label for="kota_tujuan" class="block text-sm font-medium text-gray-700">Kota Tujuan</label>
                <input type="text" id="kota_tujuan" name="kota_tujuan" value="{{ old('kota_tujuan', $rute->kota_tujuan) }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('kota_tujuan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jarak Estimasi --}}
            <div>
                <label for="jarak_estimasi" class="block text-sm font-medium text-gray-700">Jarak / Estimasi</label>
                <input type="text" id="jarak_estimasi" name="jarak_estimasi" value="{{ old('jarak_estimasi', $rute->jarak_estimasi) }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('jarak_estimasi')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Harga Tiket --}}
            <div>
                <label for="harga_tiket" class="block text-sm font-medium text-gray-700">Harga Tiket</label>
                <input type="text" id="harga_tiket" name="harga_tiket" value="{{ old('harga_tiket', $rute->harga_tiket) }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('harga_tiket')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status Rute --}}
            <div>
                <label for="status_rute" class="block text-sm font-medium text-gray-700">Status Rute</label>
                <select id="status_rute" name="status_rute" required
                        class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="Aktif" {{ old('status_rute', $rute->status_rute) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status_rute', $rute->status_rute) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status_rute')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.rute') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
