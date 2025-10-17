<?php

namespace Tests\Unit\Models;

use App\Models\Rute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RuteTest extends TestCase
{
    use RefreshDatabase;

    public function test_rute_has_fillable_attributes()
    {
        $fillable = ['kota_asal', 'kota_tujuan', 'jarak_estimasi', 'harga_tiket', 'status_rute'];
        $this->assertEquals($fillable, (new Rute)->getFillable());
    }
}
