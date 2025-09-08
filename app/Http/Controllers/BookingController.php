<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\WhatsappService;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('jadwal')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('jadwal_id');

        return view('user.riwayat', compact('bookings'));
    }

    public function create($jadwal_id = null)
    {
        $jadwals = Jadwal::orderBy('tanggal')->get();
        return view('user.pesan', compact('jadwals', 'jadwal_id'));
    }

    public function pilihKursi($jadwal_id)
    {
        $jadwal = Jadwal::findOrFail($jadwal_id);

        // Kursi yang sudah dibooking dengan status setuju (approved)
        $bookedSeats = Booking::where('jadwal_id', $jadwal_id)
            ->where('status', 'setuju')
            ->pluck('seat_number')
            ->toArray();

        // Layout kursi minibus (7 kursi)
        $seats = ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'B3'];

        return view('booking.kursi', compact('jadwal', 'seats', 'bookedSeats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'seats'     => 'required|array|min:1',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        // Cek batas waktu pemesanan (H-1 jam)
        $waktuKeberangkatan = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam);
        $batasPesan = $waktuKeberangkatan->copy()->subHour();
        $waktuSekarang = Carbon::now();

        // Debug logging
        Log::info('Booking Time Check:', [
            'waktu_keberangkatan' => $waktuKeberangkatan->format('Y-m-d H:i:s'),
            'batas_pesan' => $batasPesan->format('Y-m-d H:i:s'),
            'waktu_sekarang' => $waktuSekarang->format('Y-m-d H:i:s'),
            'jadwal_id' => $jadwal->id,
            'jadwal_tanggal' => $jadwal->tanggal,
            'jadwal_jam' => $jadwal->jam
        ]);

        if ($waktuSekarang->greaterThanOrEqualTo($batasPesan)) {
            Log::warning('Booking rejected - too close to departure time', [
                'waktu_sekarang' => $waktuSekarang->format('Y-m-d H:i:s'),
                'batas_pesan' => $batasPesan->format('Y-m-d H:i:s'),
                'difference_minutes' => $waktuSekarang->diffInMinutes($batasPesan)
            ]);

            return back()->with('error', 'Pemesanan ditutup. Minimal 1 jam sebelum keberangkatan.');
        }

        $firstBooking = null;

        foreach ($request->seats as $seat) {
            // Cek apakah kursi sudah diambil dengan status setuju (approved)
            if (Booking::where('jadwal_id', $jadwal->id)
                ->where('seat_number', $seat)
                ->where('status', 'setuju')
                ->exists()
            ) {
                return back()->withErrors(['seat' => "Kursi $seat sudah dipesan."]);
            }

            // Simpan tiap kursi sebagai booking dengan status pending
            $booking = Booking::create([
                'user_id'       => Auth::id(),
                'jadwal_id'     => $jadwal->id,
                'seat_number'   => $seat,
                'status'        => 'pending',
                'jadwal_tanggal' => $jadwal->tanggal,
                'jadwal_jam'    => $jadwal->jam,
            ]);

            if (!$firstBooking) {
                $firstBooking = $booking;
            }
        }

        // Kirim notif admin pakai booking pertama
        if ($firstBooking) {
            app(WhatsappService::class)->notifyAdminBooking($firstBooking);
        }

        return redirect()->route('riwayat')->with('success', 'Tiket berhasil dipesan!');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,setuju,batal'
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status berhasil diperbarui');
    }

    public function getSeats($id)
    {
        $bookedSeats = Booking::where('jadwal_id', $id)
            ->where('status', 'setuju')
            ->pluck('seat_number')
            ->toArray();

        return response()->json($bookedSeats);
    }
}
