<?php

namespace Database\Factories;

use App\Models\Mobil;
use Illuminate\Database\Eloquent\Factories\Factory;

class MobilFactory extends Factory
{
    protected $model = Mobil::class;

    public function definition(): array
    {
        return [
            'nomor_polisi' => $this->faker->unique()->regexify('[A-Z]{1,2} [0-9]{1,4} [A-Z]{1,3}'),
            'jenis' => $this->faker->randomElement(['Minibus', 'Bus', 'SUV']),
            'kapasitas' => $this->faker->numberBetween(7, 50),
            'tahun' => $this->faker->numberBetween(2010, 2023),
            'merk' => $this->faker->company(),
            'status' => $this->faker->randomElement(['Aktif', 'Tidak Aktif']),
        ];
    }
}
