<?php

namespace Tests\Unit\Models;

use App\Models\Supir;
use App\Models\Mobil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupirTest extends TestCase
{
    use RefreshDatabase;

    public function test_supir_belongs_to_mobil()
    {
        $mobil = Mobil::factory()->create();
        $supir = Supir::factory()->create(['mobil_id' => $mobil->id]);

        $this->assertInstanceOf(Mobil::class, $supir->mobil);
        $this->assertEquals($mobil->id, $supir->mobil->id);
    }

    public function test_supir_has_fillable_attributes()
    {
        $fillable = ['nama', 'telepon', 'mobil_id'];
        $this->assertEquals($fillable, (new Supir)->getFillable());
    }
}
