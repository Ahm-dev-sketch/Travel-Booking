<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Mobil;
use App\Models\Supir;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Booking::factory()->create(['status' => 'setuju']);

        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas(['totalUsers', 'totalJadwal', 'totalBooking', 'totalPendapatanBulanIni']);
    }

    public function test_jadwals_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $jadwal = Jadwal::factory()->create();

        $response = $this->get('/admin/jadwals');

        $response->assertStatus(200);
        $response->assertViewIs('admin.jadwals');
        $response->assertViewHas('jadwals');
    }

    public function test_create_jadwal()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $rute = Rute::factory()->create();
        $mobil = Mobil::factory()->create();

        $response = $this->post('/admin/jadwals', [
            'rute_id' => $rute->id,
            'mobil_id' => $mobil->id,
            'tanggal' => now()->addDay()->format('Y-m-d'),
            'jam' => '10:00',
            'harga' => 50000
        ]);

        $response->assertRedirect('/admin/jadwals');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('jadwals', ['rute_id' => $rute->id, 'mobil_id' => $mobil->id]);
    }

    public function test_update_jadwal()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $jadwal = Jadwal::factory()->create();

        $response = $this->put("/admin/jadwals/{$jadwal->id}", [
            'rute_id' => $jadwal->rute_id,
            'tanggal' => now()->addDays(2)->format('Y-m-d'),
            'jam' => '11:00',
            'harga' => 60000
        ]);

        $response->assertRedirect('/admin/jadwals');
        $response->assertSessionHas('success');
        $this->assertEquals('11:00', $jadwal->fresh()->jam);
    }

    public function test_delete_jadwal()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $jadwal = Jadwal::factory()->create();

        $response = $this->delete("/admin/jadwals/{$jadwal->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('jadwals', ['id' => $jadwal->id]);
    }

    public function test_bookings_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $booking = Booking::factory()->create();

        $response = $this->get('/admin/bookings');

        $response->assertStatus(200);
        $response->assertViewIs('admin.bookings');
        $response->assertViewHas('bookings');
    }

    public function test_update_booking_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $booking = Booking::factory()->create(['status' => 'pending']);

        $response = $this->put("/admin/bookings/{$booking->id}", [
            'status' => 'setuju'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('setuju', $booking->fresh()->status);
    }

    public function test_rute_crud_operations()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create
        $response = $this->post('/admin/rute', [
            'kota_asal' => 'Jakarta',
            'kota_tujuan' => 'Bandung',
            'jarak_estimasi' => '150 km',
            'harga_tiket' => '50000',
            'status_rute' => 'Aktif'
        ]);

        $response->assertRedirect('/admin/rute');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('rutes', ['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung']);

        $rute = Rute::where('kota_asal', 'Jakarta')->first();

        // Update
        $response = $this->put("/admin/rute/{$rute->id}", [
            'kota_asal' => 'Jakarta',
            'kota_tujuan' => 'Surabaya',
            'jarak_estimasi' => '700 km',
            'harga_tiket' => '100000',
            'status_rute' => 'Aktif'
        ]);

        $response->assertRedirect('/admin/rute');
        $response->assertSessionHas('success');
        $this->assertEquals('Surabaya', $rute->fresh()->kota_tujuan);

        // Delete
        $response = $this->delete("/admin/rute/{$rute->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('rutes', ['id' => $rute->id]);
    }

    public function test_mobil_crud_operations()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create
        $response = $this->post('/admin/mobil', [
            'nomor_polisi' => 'B 1234 ABC',
            'jenis' => 'Minibus',
            'kapasitas' => 12,
            'tahun' => 2020,
            'merk' => 'Toyota',
            'status' => 'Aktif'
        ]);

        $response->assertRedirect('/admin/mobil');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('mobils', ['nomor_polisi' => 'B 1234 ABC']);

        $mobil = Mobil::where('nomor_polisi', 'B 1234 ABC')->first();

        // Update
        $response = $this->put("/admin/mobil/{$mobil->id}", [
            'nomor_polisi' => 'B 1234 ABC',
            'jenis' => 'Bus',
            'kapasitas' => 20,
            'tahun' => 2021,
            'merk' => 'Mercedes',
            'status' => 'Aktif'
        ]);

        $response->assertRedirect('/admin/mobil');
        $response->assertSessionHas('success');
        $this->assertEquals('Bus', $mobil->fresh()->jenis);

        // Delete
        $response = $this->delete("/admin/mobil/{$mobil->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('mobils', ['id' => $mobil->id]);
    }

    public function test_supir_crud_operations()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $mobil = Mobil::factory()->create();

        // Create
        $response = $this->post('/admin/supir', [
            'nama' => 'John Doe',
            'telepon' => '08123456789',
            'mobil_id' => $mobil->id
        ]);

        $response->assertRedirect('/admin/supir');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('supirs', ['nama' => 'John Doe']);

        $supir = Supir::where('nama', 'John Doe')->first();

        // Update
        $response = $this->put("/admin/supir/{$supir->id}", [
            'nama' => 'Jane Doe',
            'telepon' => '08123456789',
            'mobil_id' => $mobil->id
        ]);

        $response->assertRedirect('/admin/supir');
        $response->assertSessionHas('success');
        $this->assertEquals('Jane Doe', $supir->fresh()->nama);

        // Delete
        $response = $this->delete("/admin/supir/{$supir->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('supirs', ['id' => $supir->id]);
    }

    public function test_pelanggan_crud_operations()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create
        $response = $this->post('/admin/pelanggan', [
            'name' => 'John Customer',
            'whatsapp_number' => '628123456789',
            'password' => 'password123',
            'role' => 'user',
            'email' => 'john@example.com'
        ]);

        $response->assertRedirect('/admin/pelanggan');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['name' => 'John Customer']);

        $customer = User::where('name', 'John Customer')->first();

        // Update
        $response = $this->put("/admin/pelanggan/{$customer->id}", [
            'name' => 'Jane Customer',
            'whatsapp_number' => '628123456789',
            'role' => 'user',
            'email' => 'jane@example.com'
        ]);

        $response->assertRedirect('/admin/pelanggan');
        $response->assertSessionHas('success');
        $this->assertEquals('Jane Customer', $customer->fresh()->name);

        // Delete
        $response = $this->delete("/admin/pelanggan/{$customer->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $customer->id]);
    }

    public function test_laporan_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get('/admin/laporan');

        $response->assertStatus(200);
        $response->assertViewIs('admin.laporan');
        $response->assertViewHas(['totalPendapatan', 'pendapatanBulanIni', 'transaksiSelesai']);
    }
}
