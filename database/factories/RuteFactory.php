<?php

namespace Database\Factories;

use App\Models\Rute;
use Illuminate\Database\Eloquent\Factories\Factory;

class RuteFactory extends Factory
{
    protected $model = Rute::class;

    public function definition(): array
    {
        return [
            'kota_asal' => $this->faker->city(),
            'kota_tujuan' => $this->faker->city(),
            'jarak_estimasi' => $this->faker->numberBetween(50, 500) . ' km',
            'harga_tiket' => $this->faker->numberBetween(50000, 200000),
            'status_rute' => $this->faker->randomElement(['Aktif', 'Tidak Aktif']),
        ];
    }
}
