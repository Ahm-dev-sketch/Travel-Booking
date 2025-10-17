<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Mobil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_wizard_step1()
    {
        $response = $this->get('/pesan-tiket');

        $response->assertStatus(200);
        $response->assertViewIs('booking.step1');
    }

    public function test_process_step1_success()
    {
        $response = $this->post('/pesan-tiket/step1', [
            'kota_awal' => 'Jakarta',
            'kota_tujuan' => 'Bandung',
            'tanggal' => now()->addDay()->format('Y-m-d')
        ]);

        $response->assertRedirect('/pesan-tiket/step2');
        $this->assertEquals('Jakarta', session('booking_step1')['kota_asal']);
    }

    public function test_booking_wizard_step2()
    {
        $rute = Rute::factory()->create(['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung']);
        $jadwal = Jadwal::factory()->create(['rute_id' => $rute->id, 'tanggal' => now()->addDay()->format('Y-m-d')]);

        $this->withSession(['booking_step1' => [
            'kota_asal' => 'Jakarta',
            'kota_tujuan' => 'Bandung',
            'tanggal' => now()->addDay()->format('Y-m-d')
        ]]);

        $response = $this->get('/pesan-tiket/step2');

        $response->assertStatus(200);
        $response->assertViewIs('booking.step2');
    }

    public function test_process_step2_success()
    {
        $rute = Rute::factory()->create(['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung']);
        $jadwal = Jadwal::factory()->create(['rute_id' => $rute->id, 'tanggal' => now()->addDay()->format('Y-m-d')]);

        $response = $this->post('/pesan-tiket/step2', [
            'jadwal_id' => $jadwal->id
        ]);

        $response->assertRedirect('/pesan-tiket/step3');
        $this->assertEquals($jadwal->id, session('booking_step2')['jadwal']->id);
    }

    public function test_booking_wizard_step3()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $rute = Rute::factory()->create();
        $mobil = Mobil::factory()->create();
        $jadwal = Jadwal::factory()->create(['rute_id' => $rute->id, 'mobil_id' => $mobil->id]);

        $this->withSession([
            'booking_step1' => ['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung', 'tanggal' => now()->addDay()->format('Y-m-d')],
            'booking_step2' => ['jadwal' => $jadwal]
        ]);

        $response = $this->get('/pesan-tiket/step3');

        $response->assertStatus(200);
        $response->assertViewIs('booking.step3');
    }

    public function test_process_step3_success()
    {
        Http::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        $rute = Rute::factory()->create();
        $mobil = Mobil::factory()->create();
        $jadwal = Jadwal::factory()->create([
            'rute_id' => $rute->id,
            'mobil_id' => $mobil->id,
            'tanggal' => now()->addDay()->format('Y-m-d'),
            'jam' => '10:00'
        ]);

        $this->withSession([
            'booking_step1' => ['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung', 'tanggal' => now()->addDay()->format('Y-m-d')],
            'booking_step2' => ['jadwal' => $jadwal]
        ]);

        $response = $this->post('/pesan-tiket/step3', [
            'seats' => ['A1', 'A2']
        ]);

        $response->assertRedirect('/riwayat');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', ['user_id' => $user->id, 'jadwal_id' => $jadwal->id, 'seat_number' => 'A1']);
        $this->assertDatabaseHas('bookings', ['user_id' => $user->id, 'jadwal_id' => $jadwal->id, 'seat_number' => 'A2']);
    }

    public function test_process_step3_seat_already_booked()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $rute = Rute::factory()->create();
        $mobil = Mobil::factory()->create();
        $jadwal = Jadwal::factory()->create([
            'rute_id' => $rute->id,
            'mobil_id' => $mobil->id,
            'tanggal' => now()->addDay()->format('Y-m-d'),
            'jam' => '10:00'
        ]);

        Booking::factory()->create([
            'jadwal_id' => $jadwal->id,
            'seat_number' => 'A1',
            'status' => 'setuju'
        ]);

        $this->withSession([
            'booking_step1' => ['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung', 'tanggal' => now()->addDay()->format('Y-m-d')],
            'booking_step2' => ['jadwal' => $jadwal]
        ]);

        $response = $this->post('/pesan-tiket/step3', [
            'seats' => ['A1']
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['seat']);
    }

    public function test_process_step3_booking_too_close_to_departure()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $rute = Rute::factory()->create();
        $mobil = Mobil::factory()->create();
        $jadwal = Jadwal::factory()->create([
            'rute_id' => $rute->id,
            'mobil_id' => $mobil->id,
            'tanggal' => now()->format('Y-m-d'),
            'jam' => now()->addMinutes(30)->format('H:i')
        ]);

        $this->withSession([
            'booking_step1' => ['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Bandung', 'tanggal' => now()->format('Y-m-d')],
            'booking_step2' => ['jadwal' => $jadwal]
        ]);

        $response = $this->post('/pesan-tiket/step3', [
            'seats' => ['A1']
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_get_seats()
    {
        $jadwal = Jadwal::factory()->create();
        Booking::factory()->create(['jadwal_id' => $jadwal->id, 'seat_number' => 'A1', 'status' => 'setuju']);
        Booking::factory()->create(['jadwal_id' => $jadwal->id, 'seat_number' => 'A2', 'status' => 'pending']);

        $response = $this->get("/jadwal/{$jadwal->id}/seats");

        $response->assertStatus(200);
        $response->assertJson(['A1']); // Only approved bookings should be returned
    }

    public function test_index_bookings()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/riwayat');

        $response->assertStatus(200);
        $response->assertViewIs('user.riwayat');
        $response->assertViewHas('bookings');
    }

    public function test_update_booking_status()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $booking = Booking::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $response = $this->patch("/booking/{$booking->id}/status", [
            'status' => 'setuju'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('setuju', $booking->fresh()->status);
    }
}
