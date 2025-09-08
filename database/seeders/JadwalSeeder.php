<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Mobil;
use App\Models\Rute;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil mobil
        $hiace = Mobil::where('merk', 'Toyota Hiace')->first();
        $elf   = Mobil::where('merk', 'Isuzu Elf')->first();

        // Ambil rute
        $jakartaBandung = Rute::where('kota_asal', 'Jakarta')->where('kota_tujuan', 'Bandung')->first();
        $bandungJogja   = Rute::where('kota_asal', 'Bandung')->where('kota_tujuan', 'Yogyakarta')->first();

        if (!$hiace || !$elf || !$jakartaBandung || !$bandungJogja) {
            $this->command->warn('⚠️ Data mobil/rute belum lengkap, JadwalSeeder dilewati.');
            return;
        }

        // Jadwal Jakarta → Bandung
        Jadwal::create([
            'rute_id' => $jakartaBandung->id,
            'mobil_id' => $hiace->id,
            'tanggal' => now()->addDays(1)->format('Y-m-d'),
            'jam' => '08:00:00',
            'harga' => 150000,
        ]);

        Jadwal::create([
            'rute_id' => $jakartaBandung->id,
            'mobil_id' => $hiace->id,
            'tanggal' => now()->addDays(1)->format('Y-m-d'),
            'jam' => '14:00:00',
            'harga' => 150000,
        ]);

        Jadwal::create([
            'rute_id' => $jakartaBandung->id,
            'mobil_id' => $elf->id,
            'tanggal' => now()->addDays(2)->format('Y-m-d'),
            'jam' => '10:00:00',
            'harga' => 150000,
        ]);

        // Jadwal Bandung → Yogyakarta
        Jadwal::create([
            'rute_id' => $bandungJogja->id,
            'mobil_id' => $hiace->id,
            'tanggal' => now()->addDays(3)->format('Y-m-d'),
            'jam' => '09:30:00',
            'harga' => 300000,
        ]);

        Jadwal::create([
            'rute_id' => $bandungJogja->id,
            'mobil_id' => $elf->id,
            'tanggal' => now()->addDays(3)->format('Y-m-d'),
            'jam' => '15:30:00',
            'harga' => 300000,
        ]);
    }
}
