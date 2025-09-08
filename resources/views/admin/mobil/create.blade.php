@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Mobil
    </h1>

    <div class="bg-white p-6 rounded shadow max-w-lg">
        <form id="formMobil" action="{{ route('admin.mobil.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Nomor Polisi --}}
            <div>
                <label for="nomor_polisi" class="block text-sm font-medium text-gray-700">Nomor Polisi</label>
                <input type="text" id="nomor_polisi" name="nomor_polisi" value="{{ old('nomor_polisi') }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Jenis --}}
            <div>
                <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis Mobil</label>
                <input type="text" id="jenis" name="jenis" value="{{ old('jenis') }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Kapasitas --}}
            <div>
                <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" required min="1"
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Tahun --}}
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                <input type="number" id="tahun" name="tahun" value="{{ old('tahun') }}" required min="1900" max="{{ date('Y') + 1 }}"
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Merk --}}
            <div>
                <label for="merk" class="block text-sm font-medium text-gray-700">Merk</label>
                <input type="text" id="merk" name="merk" value="{{ old('merk') }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" required
                        class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan
                </button>
                <a href="{{ route('admin.mobil') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('formMobil').addEventListener('submit', function(e) {
    e.preventDefault(); // stop submit dulu
    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data mobil akan ditambahkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Iya, simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
});
</script>
@endpush
