<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log; // Import Log facade
use App\Models\Jadwal;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusUpdated;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        // Hitung total pendapatan bulan ini dari booking yang disetujui
        $totalPendapatanBulanIni = Booking::where('status', 'setuju')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('jadwal')
            ->get()
            ->sum(function($booking) {
                return $booking->jadwal->harga;
            });

        // Hitung jumlah pemesanan bulan ini
        $jumlahPemesananBulanIni = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Hitung perjalanan aktif (jadwal yang tanggalnya >= hari ini)
        $perjalananAktif = Jadwal::where('tanggal', '>=', now()->format('Y-m-d'))
            ->count();

        // Hitung total semua pelanggan
        $totalPelanggan = User::count();

        // Hitung pendapatan 7 hari terakhir untuk chart
        $pendapatan7Hari = [];
        $labels7Hari = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels7Hari[] = $date->format('d M');

            $pendapatanHari = Booking::where('status', 'setuju')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->with('jadwal')
                ->get()
                ->sum(function($booking) {
                    return $booking->jadwal->harga;
                });

            $pendapatan7Hari[] = $pendapatanHari;
        }

        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalJadwal' => Jadwal::count(),
            'totalBooking' => Booking::count(),
            'totalPendapatanBulanIni' => $totalPendapatanBulanIni,
            'jumlahPemesananBulanIni' => $jumlahPemesananBulanIni,
            'perjalananAktif' => $perjalananAktif,
            'totalPelanggan' => $totalPelanggan,
            'pendapatan7Hari' => $pendapatan7Hari,
            'labels7Hari' => $labels7Hari,
        ]);
    }

    // Kelola Jadwal (list)
    public function jadwals(Request $request)
    {
        $search = $request->input('search');

        $jadwals = Jadwal::when($search, function($query, $search) {
            return $query->where('tujuan', 'like', "%{$search}%")
                        ->orWhere('tanggal', 'like', "%{$search}%")
                        ->orWhere('jam', 'like', "%{$search}%")
                        ->orWhere('harga', 'like', "%{$search}%");
        })->latest()->get();

        return view('admin.jadwals', compact('jadwals', 'search'));
    }

    // Form tambah jadwal
    public function createJadwal()
    {
        return view('admin.jadwals.create');
    }

    // Simpan jadwal baru
    public function storeJadwal(Request $request)
    {
        $request->validate([
            'tujuan' => 'required|string',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'harga' => 'required|integer',
        ]);

        Jadwal::create($request->only(['tujuan', 'tanggal', 'jam', 'harga']));

        return redirect()->route('admin.jadwals')->with('success', 'Jadwal berhasil ditambahkan');
    }

    // Form edit jadwal
    public function editJadwal(Jadwal $jadwal)
    {
        return view('admin.jadwals.edit', compact('jadwal'));
    }

    // Update jadwal
    public function updateJadwal(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'tujuan' => 'required|string',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'harga' => 'required|integer',
        ]);

        $jadwal->update($request->only(['tujuan', 'tanggal', 'jam', 'harga']));

        return redirect()->route('admin.jadwals')->with('success', 'Jadwal berhasil diperbarui');
    }

    // Hapus jadwal
    public function destroyJadwal(Jadwal $jadwal)
    {
        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus');
    }

    // Kelola Booking
    public function bookings(Request $request)
    {
        $search = $request->input('search');

        $bookings = Booking::with(['user', 'jadwal'])
            ->when($search, function($query, $search) {
                return $query->whereHas('user', function($q) use ($search) {
                           $q->where('name', 'like', "%{$search}%");
                       })
                       ->orWhereHas('jadwal', function($q) use ($search) {
                           $q->where('tujuan', 'like', "%{$search}%")
                             ->orWhere('tanggal', 'like', "%{$search}%");
                       })
                       ->orWhere('seat_number', 'like', "%{$search}%")
                       ->orWhere('status', 'like', "%{$search}%");
            })->latest()->get();

        return view('admin.bookings', compact('bookings', 'search'));
    }

    // Kelola Pelanggan
    public function pelanggan(Request $request)
    {
        $search = $request->input('search');

        $customers = User::when($search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('role', 'like', "%{$search}%");
        })->get();

        return view('admin.pelanggan', compact('customers', 'search'));
    }

    // Form edit pelanggan
    public function editPelanggan(User $customer)
    {
        return view('admin.pelanggan.edit', compact('customer'));
    }

    // Update pelanggan
    public function updatePelanggan(Request $request, User $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'role' => 'required|in:user,admin'
        ]);

        $customer->update($request->only(['name', 'email', 'role']));

        return redirect()->route('admin.pelanggan')->with('success', 'Data pelanggan berhasil diperbarui');
    }

    // Form tambah pelanggan
    public function createPelanggan()
    {
        return view('admin.pelanggan.create');
    }

    // Simpan pelanggan baru
    public function storePelanggan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin'
        ]);

        $userData = $request->only(['name', 'email', 'role']);
        $userData['password'] = bcrypt($request->password);

        User::create($userData);

        return redirect()->route('admin.pelanggan')->with('success', 'Berhasil Menambahkan');
    }

    // Hapus pelanggan
    public function destroyPelanggan(User $customer)
    {
        $customer->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus');
    }

    // Laporan pendapatan
    public function laporan()
    {
        // Hitung total pendapatan
        $totalPendapatan = Booking::where('status', 'setuju')->with('jadwal')->get()->sum(function($booking) {
            return $booking->jadwal->harga;
        });

        // Hitung pendapatan bulan ini
        $pendapatanBulanIni = Booking::where('status', 'setuju')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('jadwal')
            ->get()
            ->sum(function($booking) {
                return $booking->jadwal->harga;
            });

        // Hitung transaksi selesai
        $transaksiSelesai = Booking::where('status', 'setuju')->count();

        // Hitung pendapatan 7 hari terakhir untuk chart
        $pendapatan7Hari = [];
        $labels7Hari = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels7Hari[] = $date->format('d M');

            $pendapatanHari = Booking::where('status', 'setuju')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->with('jadwal')
                ->get()
                ->sum(function($booking) {
                    return $booking->jadwal->harga;
                });

            $pendapatan7Hari[] = $pendapatanHari;
        }

        return view('admin.laporan', [
            'totalPendapatan' => $totalPendapatan,
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'transaksiSelesai' => $transaksiSelesai,
            'pendapatan7Hari' => $pendapatan7Hari,
            'labels7Hari' => $labels7Hari,
        ]);
    }

    // Update status booking
    public function updateBooking(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,setuju,batal'
        ]);

        $oldStatus = $booking->status;
        $booking->update(['status' => $request->status]);

        // Kirim notifikasi email jika status berubah
        if ($oldStatus !== $request->status) {
            try {
                Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking));
            } catch (\Exception $e) {
                // Log error jika email gagal dikirim, tapi tetap lanjutkan proses
                Log::error('Gagal mengirim email notifikasi: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Status booking diperbarui!');
    }
}
