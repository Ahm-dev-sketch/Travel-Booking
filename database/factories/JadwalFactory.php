<?php

namespace Database\Factories;

use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Mobil;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalFactory extends Factory
{
    protected $model = Jadwal::class;

    public function definition(): array
    {
        return [
            'tanggal' => $this->faker->date(),
            'jam' => $this->faker->time(),
            'harga' => $this->faker->numberBetween(50000, 200000),
            'rute_id' => Rute::factory(),
            'mobil_id' => Mobil::factory(),
        ];
    }
}
