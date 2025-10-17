<?php

namespace Tests\Unit\Models;

use App\Models\Booking;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_belongs_to_user()
    {
        $user = User::factory()->create();
        $jadwal = Jadwal::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id, 'jadwal_id' => $jadwal->id]);

        $this->assertInstanceOf(User::class, $booking->user);
        $this->assertEquals($user->id, $booking->user->id);
    }

    public function test_booking_belongs_to_jadwal()
    {
        $user = User::factory()->create();
        $jadwal = Jadwal::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id, 'jadwal_id' => $jadwal->id]);

        $this->assertInstanceOf(Jadwal::class, $booking->jadwal);
        $this->assertEquals($jadwal->id, $booking->jadwal->id);
    }

    public function test_booking_has_fillable_attributes()
    {
        $fillable = ['user_id', 'jadwal_id', 'seat_number', 'status', 'jadwal_tanggal', 'jadwal_jam'];
        $this->assertEquals($fillable, (new Booking)->getFillable());
    }
}
