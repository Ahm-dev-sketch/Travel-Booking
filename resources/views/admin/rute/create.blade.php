@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Rute
    </h1>

    <div class="bg-white p-6 rounded shadow max-w-lg">
        <form id="formRute" action="{{ route('admin.rute.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Kota Asal --}}
            <div>
                <label for="kota_asal" class="block text-sm font-medium text-gray-700">Kota Asal</label>
                <input type="text" id="kota_asal" name="kota_asal" value="{{ old('kota_asal') }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Kota Tujuan --}}
            <div>
                <label for="kota_tujuan" class="block text-sm font-medium text-gray-700">Kota Tujuan</label>
                <input type="text" id="kota_tujuan" name="kota_tujuan" value="{{ old('kota_tujuan') }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Jarak Estimasi --}}
            <div>
                <label for="jarak_estimasi" class="block text-sm font-medium text-gray-700">Jarak / Estimasi</label>
                <input type="text" id="jarak_estimasi" name="jarak_estimasi" value="{{ old('jarak_estimasi') }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Harga Tiket --}}
            <div>
                <label for="harga_tiket" class="block text-sm font-medium text-gray-700">Harga Tiket</label>
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 font-medium">RP</span>
                    <input type="text" id="harga_tiket" name="harga_tiket" value="{{ old('harga_tiket') }}" required
                           class="pl-12 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none"
                           placeholder="0">
                </div>
            </div>

            {{-- Status Rute --}}
            <div>
                <label for="status_rute" class="block text-sm font-medium text-gray-700">Status Rute</label>
                <select id="status_rute" name="status_rute" required
                        class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="Aktif" {{ old('status_rute') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status_rute') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan
                </button>
                <a href="{{ route('admin.rute') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
// Function to format number with Indonesian format (dots as thousands separator)
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Function to unformat number (remove dots)
function unformatNumber(str) {
    return str.replace(/\./g, '');
}

document.getElementById('harga_tiket').addEventListener('input', function(e) {
    let value = e.target.value;

    // Remove any non-numeric characters except dots
    value = value.replace(/[^\d.]/g, '');

    // Remove existing dots to reformat
    value = unformatNumber(value);

    // Format with dots
    if (value) {
        value = formatNumber(value);
    }

    e.target.value = value;
});

document.getElementById('formRute').addEventListener('submit', function(e) {
    e.preventDefault(); // stop submit dulu

    // Remove formatting before submit
    const hargaInput = document.getElementById('harga_tiket');
    hargaInput.value = unformatNumber(hargaInput.value);

    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data rute akan ditambahkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Iya, simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
