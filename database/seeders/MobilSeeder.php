<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Mobil::create([
            'nomor_polisi' => 'B 1234 ABC',
            'jenis' => 'Minibus',
            'kapasitas' => 7,
            'tahun' => 2020,
            'merk' => 'Toyota Hiace',
            'status' => 'Aktif'
        ]);

        \App\Models\Mobil::create([
            'nomor_polisi' => 'B 5678 DEF',
            'jenis' => 'Minibus',
            'kapasitas' => 7,
            'tahun' => 2021,
            'merk' => 'Toyota Hiace',
            'status' => 'Aktif'
        ]);

        \App\Models\Mobil::create([
            'nomor_polisi' => 'B 9012 GHI',
            'jenis' => 'Minibus',
            'kapasitas' => 7,
            'tahun' => 2019,
            'merk' => 'Isuzu Elf',
            'status' => 'Aktif'
        ]);
    }
}
