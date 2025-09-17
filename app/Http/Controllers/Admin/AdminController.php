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

        $jadwals = Jadwal::with('rute')
            ->when($search, function($query, $search) {
                return $query->whereHas('rute', function($q) use ($search) {
                    $q->where('kota_asal', 'like', "%{$search}%")
                      ->orWhere('kota_tujuan', 'like', "%{$search}%");
                })
                ->orWhere('tanggal', 'like', "%{$search}%")
                ->orWhere('jam', 'like', "%{$search}%")
                ->orWhere('harga', 'like', "%{$search}%");
            })->latest()->paginate(10);

        return view('admin.jadwals', compact('jadwals', 'search'));
    }

    // Form tambah jadwal
    public function createJadwal()
    {
        $rutes = \App\Models\Rute::all();
        $mobils = \App\Models\Mobil::all();
        return view('admin.jadwals.create', compact('rutes', 'mobils'));
    }

    // Simpan jadwal baru
    public function storeJadwal(Request $request)
    {
        $request->validate([
            'rute_id' => 'required|exists:rutes,id',
            'mobil_id' => 'required|exists:mobils,id',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'harga' => 'required|integer',
        ]);

        Jadwal::create($request->only(['rute_id', 'mobil_id', 'tanggal', 'jam', 'harga']));

        return redirect()->route('admin.jadwals')->with('success', 'Jadwal berhasil ditambahkan');
    }

    // Form edit jadwal
    public function editJadwal(Jadwal $jadwal)
    {
        $rutes = \App\Models\Rute::all();
        return view('admin.jadwals.edit', compact('jadwal', 'rutes'));
    }

    // Update jadwal
    public function updateJadwal(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'rute_id' => 'required|exists:rutes,id',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'harga' => 'required|integer',
        ]);

        $jadwal->update($request->only(['rute_id', 'tanggal', 'jam', 'harga']));

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

        $bookings = Booking::with(['user', 'jadwal.rute', 'jadwal.mobil'])
            ->when($search, function($query, $search) {
                return $query->whereHas('user', function($q) use ($search) {
                           $q->where('name', 'like', "%{$search}%");
                       })
                       ->orWhereHas('jadwal.rute', function($q) use ($search) {
                           $q->where('kota_asal', 'like', "%{$search}%")
                             ->orWhere('kota_tujuan', 'like', "%{$search}%");
                       })
                       ->orWhereHas('jadwal', function($q) use ($search) {
                           $q->where('tanggal', 'like', "%{$search}%");
                       })
                       ->orWhereHas('jadwal.mobil', function($q) use ($search) {
                           $q->where('merk', 'like', "%{$search}%")
                             ->orWhere('nomor_polisi', 'like', "%{$search}%");
                       })
                       ->orWhere('seat_number', 'like', "%{$search}%")
                       ->orWhere('status', 'like', "%{$search}%");
            })->latest()->paginate(10);

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
        })->paginate(10);

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

    // Data Rute
    public function rute(Request $request)
    {
        $search = $request->input('search');

        $query = \App\Models\Rute::query();

        if ($search) {
            $query->where('kota_asal', 'like', "%{$search}%")
                  ->orWhere('kota_tujuan', 'like', "%{$search}%")
                  ->orWhere('jarak_estimasi', 'like', "%{$search}%")
                  ->orWhere('harga_tiket', 'like', "%{$search}%")
                  ->orWhere('status_rute', 'like', "%{$search}%");
        }

        $rutes = $query->latest()->paginate(10);

        return view('admin.rute', compact('rutes', 'search'));
    }

    // Form tambah rute
    public function createRute()
    {
        return view('admin.rute.create');
    }

    // Simpan rute baru
    public function storeRute(Request $request)
    {
        $request->validate([
            'kota_asal' => 'required|string',
            'kota_tujuan' => 'required|string',
            'jarak_estimasi' => 'required|string',
            'harga_tiket' => 'required|string',
            'status_rute' => 'required|string',
        ]);

        \App\Models\Rute::create($request->only([
            'kota_asal',
            'kota_tujuan',
            'jarak_estimasi',
            'harga_tiket',
            'status_rute',
        ]));

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil ditambahkan');
    }

    // Form edit rute
    public function editRute(\App\Models\Rute $rute)
    {
        return view('admin.rute.edit', compact('rute'));
    }

    // Update rute
    public function updateRute(Request $request, \App\Models\Rute $rute)
    {
        $request->validate([
            'kota_asal' => 'required|string',
            'kota_tujuan' => 'required|string',
            'jarak_estimasi' => 'required|string',
            'harga_tiket' => 'required|string',
            'status_rute' => 'required|string',
        ]);

        $rute->update($request->only([
            'kota_asal',
            'kota_tujuan',
            'jarak_estimasi',
            'harga_tiket',
            'status_rute',
        ]));

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil diperbarui');
    }

    // Hapus rute
    public function destroyRute(\App\Models\Rute $rute)
    {
        $rute->delete();
        return back()->with('success', 'Rute berhasil dihapus');
    }

    // Data Mobil
    public function mobil(Request $request)
    {
        $search = $request->input('search');

        $query = \App\Models\Mobil::query();

        if ($search) {
            $query->where('nomor_polisi', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
        }

        $mobils = $query->latest()->paginate(10);

        return view('admin.mobil', compact('mobils', 'search'));
    }

    // Form tambah mobil
    public function createMobil()
    {
        return view('admin.mobil.create');
    }

    // Simpan mobil baru
    public function storeMobil(Request $request)
    {
        $request->validate([
            'nomor_polisi' => 'required|string|unique:mobils',
            'jenis' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'merk' => 'required|string',
            'status' => 'required|string',
        ]);

        \App\Models\Mobil::create($request->only([
            'nomor_polisi',
            'jenis',
            'kapasitas',
            'tahun',
            'merk',
            'status',
        ]));

        return redirect()->route('admin.mobil')->with('success', 'Mobil berhasil ditambahkan');
    }

    // Form edit mobil
    public function editMobil(\App\Models\Mobil $mobil)
    {
        return view('admin.mobil.edit', compact('mobil'));
    }

    // Update mobil
    public function updateMobil(Request $request, \App\Models\Mobil $mobil)
    {
        $request->validate([
            'nomor_polisi' => 'required|string|unique:mobils,nomor_polisi,' . $mobil->id,
            'jenis' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'merk' => 'required|string',
            'status' => 'required|string',
        ]);

        $mobil->update($request->only([
            'nomor_polisi',
            'jenis',
            'kapasitas',
            'tahun',
            'merk',
            'status',
        ]));

        return redirect()->route('admin.mobil')->with('success', 'Mobil berhasil diperbarui');
    }

    // Hapus mobil
    public function destroyMobil(\App\Models\Mobil $mobil)
    {
        $mobil->delete();
        return back()->with('success', 'Mobil berhasil dihapus');
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
