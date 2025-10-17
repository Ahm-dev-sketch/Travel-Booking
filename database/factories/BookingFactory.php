<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'jadwal_id' => Jadwal::factory(),
            'seat_number' => $this->faker->randomElement(['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'B3']),
            'status' => $this->faker->randomElement(['pending', 'setuju', 'batal']),
            'jadwal_tanggal' => $this->faker->date(),
            'jadwal_jam' => $this->faker->time(),
        ];
    }
}
