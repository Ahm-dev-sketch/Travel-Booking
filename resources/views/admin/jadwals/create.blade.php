@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Jadwal
    </h1>

    <div class="bg-white p-6 rounded shadow max-w-lg">
        <form id="formJadwal" action="{{ route('admin.jadwals.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Tujuan --}}
            <div>
                <label for="tujuan" class="block text-sm font-medium text-gray-700">Tujuan</label>
                <input type="text" id="tujuan" name="tujuan" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Tanggal --}}
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Jam --}}
            <div>
                <label for="jam" class="block text-sm font-medium text-gray-700">Jam</label>
                <input type="time" id="jam" name="jam" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Harga --}}
            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" id="harga" name="harga" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Tombol --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan
                </button>
                <a href="{{ route('admin.jadwals') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('formJadwal').addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Jadwal baru akan ditambahkan!",
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
