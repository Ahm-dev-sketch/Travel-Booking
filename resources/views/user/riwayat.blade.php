@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4" data-aos="fade-down">Riwayat Pemesanan Tiket Saya</h2>

    @if ($bookings->isEmpty())
        <p class="text-gray-500" data-aos="fade-up">Belum ada data pesanan.</p>
    @else
        <div class="space-y-6" data-aos="fade-up" data-aos-delay="200">
            @foreach ($bookings as $booking)
                <div class="bg-white rounded shadow p-6 border border-gray-200">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-semibold text-blue-700">No. Tiket: </span>
                            <a href="#"
                                class="font-semibold text-blue-700 hover:underline">{{ $booking->ticket_number }}</a>
                            <p class="text-gray-500 text-sm">Dipesan pada:
                                {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            @if ($booking->status == 'setuju')
                                <span
                                    class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Lunas</span>
                            @elseif($booking->status == 'pending')
                                <span
                                    class="bg-yellow-400 text-white px-3 py-1 rounded-full text-sm font-semibold">Pending</span>
                            @else
                                <span
                                    class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Batal</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-gray-700 mb-4">
                        <div>
                            <p><span class="font-semibold">Rute:</span> {{ $booking->jadwal->rute->kota_asal ?? '-' }}
                                &mdash; {{ $booking->jadwal->rute->kota_tujuan ?? '-' }}</p>
                            <p><span class="font-semibold">Jadwal:</span>
                                {{ \Carbon\Carbon::parse($booking->jadwal_tanggal)->format('l, d F Y') }} pukul
                                {{ $booking->jadwal_jam }} WIB</p>
                            <p><span class="font-semibold">Jenis Mobil:</span> {{ $booking->jadwal->mobil->jenis ?? '-' }}
                            </p>
                            <p><span class="font-semibold">Supir:</span> {{ $booking->jadwal->mobil->supir->nama ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p><span class="font-semibold">Penumpang:</span> {{ $booking->user->name ?? '-' }}</p>
                            <p><span class="font-semibold">No. Kursi:</span> {{ $booking->seat_number ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
<div class="font-semibold text-lg text-gray-900">
    Total: Rp {{ number_format($booking->jadwal->harga ?? 0, 0, ',', '.') }}
</div>
                        {{-- <a href="{{ route('booking.download', $booking->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                            </svg>
                            Download E-Ticket
                        </a> --}}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-end w-full pr-4" data-aos="fade-up" data-aos-delay="400">
            {{ $bookings->links() }}
        </div>
    @endif
@endsection
