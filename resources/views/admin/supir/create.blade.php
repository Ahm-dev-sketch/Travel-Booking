@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Supir
    </h1>

    <div class="bg-white p-6 rounded shadow max-w-lg">
        <form id="formSupir" action="{{ route('admin.supir.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Nama --}}
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Supir</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Telepon --}}
            <div>
                <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                <input type="text" id="telepon" name="telepon" value="{{ old('telepon') }}"
                       class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Mobil --}}
            <div>
                <label for="mobil_id" class="block text-sm font-medium text-gray-700">Mobil</label>
                <select id="mobil_id" name="mobil_id" required
                        class="mt-1 block w-full border rounded p-2 focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="">Pilih Mobil</option>
                    @foreach($mobils as $mobil)
                        <option value="{{ $mobil->id }}" {{ old('mobil_id') == $mobil->id ? 'selected' : '' }}>
                            {{ $mobil->merk }} ({{ $mobil->nomor_polisi }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan
                </button>
                <a href="{{ route('admin.supir') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('formSupir').addEventListener('submit', function(e) {
    e.preventDefault(); // stop submit dulu
    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data supir akan ditambahkan!",
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
