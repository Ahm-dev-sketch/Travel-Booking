<?php

namespace Database\Factories;

use App\Models\OtpToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class OtpTokenFactory extends Factory
{
    protected $model = OtpToken::class;

    public function definition(): array
    {
        return [
            'whatsapp_number' => $this->faker->phoneNumber(),
            'otp_code' => $this->faker->numerify('######'),
            'expires_at' => $this->faker->dateTimeBetween('now', '+10 minutes'),
            'used' => $this->faker->boolean(),
        ];
    }
}
