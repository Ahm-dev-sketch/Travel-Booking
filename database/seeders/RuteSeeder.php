<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Rute::create([
            'kota_asal' => 'Jakarta',
            'kota_tujuan' => 'Bandung',
            'jarak_estimasi' => '150 km / 3 jam',
            'harga_tiket' => 'Rp 150.000',
            'status_rute' => 'Aktif',
        ]);

        \App\Models\Rute::create([
            'kota_asal' => 'Bandung',
            'kota_tujuan' => 'Yogyakarta',
            'jarak_estimasi' => '400 km / 8 jam',
            'harga_tiket' => 'Rp 300.000',
            'status_rute' => 'Aktif',
        ]);
    }
}
