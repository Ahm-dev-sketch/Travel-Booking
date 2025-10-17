@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Edit Supir
    </h1>

    <div class="bg-white p-6 rounded shadow max-w-lg">
        <form action="{{ route('admin.supir.update', $supir) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Supir</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $supir->nama) }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('nama')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Telepon --}}
            <div>
                <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                <input type="text" id="telepon" name="telepon" value="{{ old('telepon', $supir->telepon) }}"
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('telepon')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mobil --}}
            <div>
                <label for="mobil_id" class="block text-sm font-medium text-gray-700">Mobil</label>
                <select id="mobil_id" name="mobil_id" required
                        class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="">Pilih Mobil</option>
                    @foreach($mobils as $mobil)
                        <option value="{{ $mobil->id }}" {{ old('mobil_id', $supir->mobil_id) == $mobil->id ? 'selected' : '' }}>
                            {{ $mobil->merk }} ({{ $mobil->nomor_polisi }})
                        </option>
                    @endforeach
                </select>
                @error('mobil_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.supir') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
