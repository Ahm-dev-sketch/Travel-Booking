<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jadwal::create([
            'tujuan' => 'Jakarta-Medan',
            'tanggal' => '2025-08-30',
            'jam' => '08:00:00',
            'harga' => 150000,
        ]);

        Jadwal::create([
            'tujuan' => 'Medan-Pekanbaru',
            'tanggal' => '2025-09-01',
            'jam' => '09:30:00',
            'harga' => 200000,
        ]);
    }
}
