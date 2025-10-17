<?php

namespace Tests\Feature;

use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_jadwals()
    {
        $rute = Rute::factory()->create(['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung']);
        $jadwal = Jadwal::factory()->create(['rute_id' => $rute->id]);

        $response = $this->get('/jadwal');

        $response->assertStatus(200);
        $response->assertViewIs('user.jadwal');
        $response->assertViewHas('jadwals');
    }

    public function test_index_jadwals_with_search()
    {
        $rute = Rute::factory()->create(['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung']);
        $jadwal = Jadwal::factory()->create(['rute_id' => $rute->id]);

        $response = $this->get('/jadwal?search=Jakarta');

        $response->assertStatus(200);
        $response->assertViewHas('jadwals');
    }

    public function test_get_booked_seats()
    {
        $jadwal = Jadwal::factory()->create();
        Booking::factory()->create([
            'jadwal_id' => $jadwal->id,
            'seat_number' => 'A1',
            'status' => 'setuju'
        ]);
        Booking::factory()->create([
            'jadwal_id' => $jadwal->id,
            'seat_number' => 'A2',
            'status' => 'pending',
            'created_at' => now()->subMinutes(45) // Older than 30 minutes
        ]);

        $response = $this->get("/jadwal/{$jadwal->id}/seats");

        $response->assertStatus(200);
        $response->assertJson(['A1']); // Only approved seats and recent pending should be returned
    }
}
