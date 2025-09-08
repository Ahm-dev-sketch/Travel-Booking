@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Edit Mobil
    </h1>

    <div class="bg-white p-6 rounded shadow max-w-lg">
        <form action="{{ route('admin.mobil.update', $mobil) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Nomor Polisi --}}
            <div>
                <label for="nomor_polisi" class="block text-sm font-medium text-gray-700">Nomor Polisi</label>
                <input type="text" id="nomor_polisi" name="nomor_polisi" value="{{ old('nomor_polisi', $mobil->nomor_polisi) }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('nomor_polisi')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis --}}
            <div>
                <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis Mobil</label>
                <input type="text" id="jenis" name="jenis" value="{{ old('jenis', $mobil->jenis) }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('jenis')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kapasitas --}}
            <div>
                <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $mobil->kapasitas) }}" required min="1"
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('kapasitas')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tahun --}}
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $mobil->tahun) }}" required min="1900" max="{{ date('Y') + 1 }}"
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('tahun')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Merk --}}
            <div>
                <label for="merk" class="block text-sm font-medium text-gray-700">Merk</label>
                <input type="text" id="merk" name="merk" value="{{ old('merk', $mobil->merk) }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                @error('merk')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" required
                        class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="Aktif" {{ old('status', $mobil->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status', $mobil->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.mobil') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
