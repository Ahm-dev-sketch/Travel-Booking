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
        // Jadwal untuk rute Jakarta - Bandung
        Jadwal::create([
            'tujuan' => 'Jakarta-Bandung',
            'tanggal' => '2025-08-30',
            'jam' => '08:00:00',
            'harga' => 150000,
        ]);

        Jadwal::create([
            'tujuan' => 'Jakarta-Bandung',
            'tanggal' => '2025-08-30',
            'jam' => '14:00:00',
            'harga' => 150000,
        ]);

        // Jadwal untuk rute Bandung - Yogyakarta
        Jadwal::create([
            'tujuan' => 'Bandung-Yogyakarta',
            'tanggal' => '2025-09-01',
            'jam' => '09:30:00',
            'harga' => 300000,
        ]);

        Jadwal::create([
            'tujuan' => 'Bandung-Yogyakarta',
            'tanggal' => '2025-09-01',
            'jam' => '15:30:00',
            'harga' => 300000,
        ]);
    }
}
