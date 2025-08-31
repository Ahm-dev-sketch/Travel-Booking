<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{

    public function getBookedSeats($jadwal_id)
    {
        $threshold = Carbon::now()->subMinutes(30);

        $bookedSeats = Booking::where('jadwal_id', $jadwal_id)
            ->where(function ($query) use ($threshold) {
                $query->where('status', 'setuju')
                      ->orWhere(function ($q) use ($threshold) {
                          $q->where('status', 'pending')
                            ->where('created_at', '>=', $threshold);
                      });
            })
            ->pluck('seat_number');

        return response()->json($bookedSeats);
    }
    public function index(Request $request)
    {
        $search = $request->input('search');

        $jadwals = Jadwal::query()
            ->when($search, function ($query, $search) {
                $query->where('tujuan', 'like', "%{$search}%")
                    ->orWhere('tanggal', 'like', "%{$search}%")
                    ->orWhere('jam', 'like', "%{$search}%");
            })
            ->orderBy('tanggal')
            ->get();

        return view('user.jadwal', compact('jadwals', 'search'));
    }
}
