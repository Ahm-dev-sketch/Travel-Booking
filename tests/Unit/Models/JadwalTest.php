<?php

namespace Tests\Unit\Models;

use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Mobil;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalTest extends TestCase
{
    use RefreshDatabase;

    public function test_jadwal_belongs_to_rute()
    {
        $rute = Rute::factory()->create();
        $mobil = Mobil::factory()->create();
        $jadwal = Jadwal::factory()->create(['rute_id' => $rute->id, 'mobil_id' => $mobil->id]);

        $this->assertInstanceOf(Rute::class, $jadwal->rute);
        $this->assertEquals($rute->id, $jadwal->rute->id);
    }

    public function test_jadwal_belongs_to_mobil()
    {
        $rute = Rute::factory()->create();
        $mobil = Mobil::factory()->create();
        $jadwal = Jadwal::factory()->create(['rute_id' => $rute->id, 'mobil_id' => $mobil->id]);

        $this->assertInstanceOf(Mobil::class, $jadwal->mobil);
        $this->assertEquals($mobil->id, $jadwal->mobil->id);
    }

    public function test_jadwal_has_many_bookings()
    {
        $rute = Rute::factory()->create();
        $mobil = Mobil::factory()->create();
        $jadwal = Jadwal::factory()->create(['rute_id' => $rute->id, 'mobil_id' => $mobil->id]);
        $booking = Booking::factory()->create(['jadwal_id' => $jadwal->id]);

        $this->assertInstanceOf(Booking::class, $jadwal->bookings->first());
        $this->assertEquals($booking->id, $jadwal->bookings->first()->id);
    }

    public function test_jadwal_has_fillable_attributes()
    {
        $fillable = ['tujuan', 'tanggal', 'jam', 'harga', 'rute_id', 'mobil_id'];
        $this->assertEquals($fillable, (new Jadwal)->getFillable());
    }
}
