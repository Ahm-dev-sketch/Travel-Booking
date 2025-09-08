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
                    d="M9 17V7m0 10a2 2 0 100 4 2 2 0 000-4zm0 0a2 2 0 110 4 2 2 0 01-4 0 2 2 0 014 0zm0 0V7a2 2 0 012-2h6a2 2 0 012 2v10M9 7a2 2 0 012-2h6a2 2 0 012 2m0 10a2 2 0 100 4 2 2 0 000-4zm0 0a2 2 0 110 4 2 2 0 01-4 0 2 2 0 014 0z" />
            </svg>
            Data Mobil
        </h1>
        <div class="flex items-center gap-4">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('admin.mobil') }}" class="flex items-center gap-2">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden shadow-sm">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari mobil..."
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

                {{-- Reset button --}}
                @if (isset($search) && $search)
                    <a href="{{ route('admin.mobil') }}"
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

            {{-- Tambah Mobil --}}
            <a href="{{ route('admin.mobil.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2 transition"
                data-aos="fade-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Mobil
            </a>
        </div>
    </div>

    {{-- Card untuk tabel --}}
    <div class="bg-white p-6 rounded shadow overflow-x-auto" data-aos="fade-up">
        <table class="w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3 border border-white">ID Mobil</th>
                    <th class="px-4 py-3 border border-white">Nomor Plat</th>
                    <th class="px-4 py-3 border border-white">Jenis Mobil</th>
                    <th class="px-4 py-3 border border-white">Kapasitas</th>
                    <th class="px-4 py-3 border border-white">Tahun / Merk</th>
                    <th class="px-4 py-3 border border-white">Status</th>
                    <th class="px-4 py-3 border border-white text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($mobils as $mobil)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border border-white">{{ $mobil->id }}</td>
                        <td class="px-4 py-2 border border-white">{{ $mobil->nomor_polisi }}</td>
                        <td class="px-4 py-2 border border-white">{{ $mobil->jenis }}</td>
                        <td class="px-4 py-2 border border-white">{{ $mobil->kapasitas }}</td>
                        <td class="px-4 py-2 border border-white">{{ $mobil->tahun }}/{{ $mobil->merk }}</td>
                        <td class="px-4 py-2 border border-white">
                            <span class="px-2 py-1 rounded text-sm bg-green-100 text-green-700">
                                {{ $mobil->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border border-white">
                            <div class="flex justify-center gap-3">
                                {{-- Edit --}}
                                <a href="{{ route('admin.mobil.edit', $mobil) }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.mobil.destroy', $mobil) }}" method="POST" class="delete-form">
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
                        <td colspan="7" class="text-center py-4 text-gray-500">Belum ada data mobil</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center" data-aos="fade-up" data-aos-delay="400">
        {{ $mobils->links() }}
    </div>
@endsection
