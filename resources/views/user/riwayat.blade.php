@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4" data-aos="fade-down">Riwayat Pemesanan</h2>

    @if ($bookings->isEmpty())
        <p class="text-gray-500" data-aos="fade-up">Belum ada data pesanan.</p>
    @else
        {{-- Card Table --}}
        <div class="bg-white rounded shadow overflow-x-auto" data-aos="fade-up" data-aos-delay="200">
            <table class="w-full border border-gray-200">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3 border">Tanggal</th>
                        <th class="px-4 py-3 border">Tujuan</th>
                        <th class="px-4 py-3 border">Jam</th>
                        <th class="px-4 py-3 border">Kursi</th>
                        <th class="px-4 py-3 border">Status Pemesanan</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-gray-50" data-aos="fade-up">
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($booking->jadwal_tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2 border">{{ $booking->jadwal->tujuan ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ $booking->jadwal_jam }}</td>
                            <td class="px-4 py-2 border">{{ $booking->seat_number }}</td>
                            <td class="px-4 py-2 border">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
    {{ $booking->status == 'setuju'
        ? 'bg-green-100 text-green-700'
        : ($booking->status == 'batal' || ($booking->status == 'pending' && \Carbon\Carbon::parse($booking->created_at)->addMinutes(30)->isPast())
            ? 'bg-red-100 text-red-700'
            : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $booking->status == 'pending' && \Carbon\Carbon::parse($booking->created_at)->addMinutes(30)->isPast() ? 'Batal' : ucfirst($booking->status) }}
                                </span>
                            </td>
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
