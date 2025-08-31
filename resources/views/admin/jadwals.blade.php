@extends('layouts.admin')

@section('content')
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

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-2" data-aos="fade-right">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 4h10M5 11h14v10H5V11z" />
            </svg>
            Kelola Jadwal
        </h1>
        <div class="flex items-center gap-4">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('admin.jadwals') }}" class="flex items-center gap-2">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden shadow-sm">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari jadwal..."
                        class="px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 hover:bg-blue-700 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <!-- Tombol Reset -->
                @if (isset($search) && $search)
                    <a href="{{ route('admin.jadwals') }}"
                        class="text-gray-600 hover:text-gray-800 flex items-center gap-1 text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset
                    </a>
                @endif
            </form>

            <a href="{{ route('admin.jadwals.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2 transition"
                data-aos="fade-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Jadwal
            </a>
        </div>
    </div>

    {{-- Card untuk tabel --}}
    <div class="bg-white p-6 rounded shadow overflow-x-auto" data-aos="fade-up">
        <table class="w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3 border border-white">ID</th>
                    <th class="px-4 py-3 border border-white">Tujuan</th>
                    <th class="px-4 py-3 border border-white">Tanggal</th>
                    <th class="px-4 py-3 border border-white">Jam</th>
                    <th class="px-4 py-3 border border-white">Harga</th>
                    <th class="px-4 py-3 border border-white text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($jadwals as $jadwal)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border border-white">{{ $jadwal->id }}</td>
                        <td class="px-4 py-2 border border-white">{{ $jadwal->tujuan }}</td>
                        <td class="px-4 py-2 border border-white">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 py-2 border border-white">{{ $jadwal->jam }}</td>
                        <td class="px-4 py-2 border border-white">Rp {{ number_format($jadwal->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border border-white">
                            <div class="flex justify-center gap-3">
                                {{-- Edit --}}
                                <a href="{{ route('admin.jadwals.edit', $jadwal) }}"
                                    class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.jadwals.destroy', $jadwal) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 flex items-center gap-1">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Belum ada jadwal</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin hapus jadwal ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
</create_file>
