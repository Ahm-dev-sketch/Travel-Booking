<?php

namespace Tests\Unit\Models;

use App\Models\Mobil;
use App\Models\Supir;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobilTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobil_has_one_supir()
    {
        $mobil = Mobil::factory()->create();
        $supir = Supir::factory()->create(['mobil_id' => $mobil->id]);

        $this->assertInstanceOf(Supir::class, $mobil->supir);
        $this->assertEquals($supir->id, $mobil->supir->id);
    }

    public function test_mobil_has_fillable_attributes()
    {
        $fillable = ['nomor_polisi', 'jenis', 'kapasitas', 'tahun', 'merk', 'status'];
        $this->assertEquals($fillable, (new Mobil)->getFillable());
    }
}
