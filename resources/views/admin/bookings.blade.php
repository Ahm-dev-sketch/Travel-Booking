@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2" data-aos="fade-down">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 17v-6a2 2 0 012-2h8m-8 0V7a2 2 0 012-2h2a2 2 0 012 2v2m0 4h2a2 2 0 012 2v6M9 17h6" />
        </svg>
        Kelola Booking
    </h2>

    <div class="overflow-x-auto bg-white p-6 rounded-lg shadow" data-aos="fade-up">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="px-6 py-3 text-center border border-white">User</th>
                    <th class="px-6 py-3 text-center border border-white">Kota Awal</th>
                    <th class="px-6 py-3 text-center border border-white">Kota Tujuan</th>
                    <th class="px-6 py-3 text-center border border-white">Tanggal</th>
                    <th class="px-6 py-3 text-center border border-white">Jam</th>
                    <th class="px-6 py-3 text-center border border-white">Mobil</th>
                    <th class="px-6 py-3 text-center border border-white">Kursi</th>
                    <th class="px-6 py-3 text-center border border-white">Status</th>
                    <th class="px-6 py-3 text-center border border-white">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->user->name }}</td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->jadwal->rute ? $booking->jadwal->rute->kota_asal : '-' }}</td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->jadwal->rute ? $booking->jadwal->rute->kota_tujuan : '-' }}</td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->jadwal_tanggal }}</td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->jadwal ? $booking->jadwal->jam : '-' }}</td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->jadwal->mobil ? $booking->jadwal->mobil->merk . ' (' . $booking->jadwal->mobil->nomor_polisi . ')' : '-' }}</td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->seat_number }}</td>
                        <td class="px-6 py-3 text-center border border-white">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $booking->status == 'setuju'
                                    ? 'bg-green-100 text-green-700'
                                    : ($booking->status == 'batal'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border border-white">
                            <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST"
                                class="flex items-center justify-center gap-2">
                                @csrf @method('PUT')
                                <select name="status"
                                    class="border rounded px-2 py-1 text-sm focus:ring focus:ring-blue-300">
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="setuju" {{ $booking->status == 'setuju' ? 'selected' : '' }}>Setuju
                                    </option>
                                    <option value="batal" {{ $booking->status == 'batal' ? 'selected' : '' }}>Batal
                                    </option>
                                </select>
                                <button type="submit"
                                    class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700 transition">
                                    Simpan
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center" data-aos="fade-up" data-aos-delay="400">
        {{ $bookings->links() }}
    </div>
@endsection
