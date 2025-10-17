<?php

namespace Database\Factories;

use App\Models\Supir;
use App\Models\Mobil;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupirFactory extends Factory
{
    protected $model = Supir::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'telepon' => $this->faker->phoneNumber(),
            'mobil_id' => Mobil::factory(),
        ];
    }
}
