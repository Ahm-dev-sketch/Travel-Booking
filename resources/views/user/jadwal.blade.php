@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4" data-aos="fade-down">
        Jadwal Keberangkatan
    </h2>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('jadwal') }}"
        class="mb-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-2" data-aos="fade-right">

        <!-- Input dengan Icon -->
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </span>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari tujuan / tanggal / jam..."
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
        </div>

        <!-- Tombol Cari -->
        <button type="submit"
            class="flex items-center justify-center gap-1 px-4 py-2 bg-blue-600 text-white
                   rounded-lg hover:bg-blue-700 transition text-sm shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
            Cari
        </button>
    </form>

    @if ($jadwals->isEmpty())
        <p class="text-gray-500" data-aos="fade-up">Tidak ada jadwal ditemukan.</p>
    @else
        {{-- Card Table --}}
        <div class="bg-white rounded shadow overflow-x-auto" data-aos="fade-up" data-aos-delay="200">
            <table class="w-full border border-gray-200">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3 border">Kota Asal</th>
                        <th class="px-4 py-3 border">Kota Tujuan</th>
                        <th class="px-4 py-3 border">Tanggal</th>
                        <th class="px-4 py-3 border">Jam</th>
                        <th class="px-4 py-3 border">Harga</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($jadwals as $index => $jadwal)
                        <tr class="hover:bg-gray-50" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <td class="px-4 py-2 border">{{ $jadwal->rute->kota_asal ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ $jadwal->rute->kota_tujuan ?? '-' }}</td>
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2 border">{{ $jadwal->jam }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($jadwal->harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 flex justify-end w-full pr-4" data-aos="fade-up" data-aos-delay="400">
            {{ $jadwals->links() }}
        </div>
    @endif
@endsection
