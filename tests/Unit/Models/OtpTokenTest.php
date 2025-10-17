<?php

namespace Tests\Unit\Models;

use App\Models\OtpToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class OtpTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_otp_token_has_fillable_attributes()
    {
        $fillable = ['whatsapp_number', 'otp_code', 'expires_at', 'used'];
        $this->assertEquals($fillable, (new OtpToken)->getFillable());
    }

    public function test_otp_token_has_casts()
    {
        $casts = (new OtpToken)->getCasts();
        $this->assertArrayHasKey('expires_at', $casts);
        $this->assertEquals('datetime', $casts['expires_at']);
        $this->assertArrayHasKey('used', $casts);
        $this->assertEquals('boolean', $casts['used']);
    }

    public function test_scope_valid_returns_valid_otp()
    {
        $validOtp = OtpToken::factory()->create([
            'whatsapp_number' => '628123456789',
            'otp_code' => '123456',
            'used' => false,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $result = OtpToken::valid('628123456789', '123456')->first();

        $this->assertNotNull($result);
        $this->assertEquals($validOtp->id, $result->id);
    }

    public function test_scope_valid_does_not_return_expired_otp()
    {
        OtpToken::factory()->create([
            'whatsapp_number' => '628123456789',
            'otp_code' => '123456',
            'used' => false,
            'expires_at' => Carbon::now()->subMinutes(1),
        ]);

        $result = OtpToken::valid('628123456789', '123456')->first();

        $this->assertNull($result);
    }

    public function test_scope_valid_does_not_return_used_otp()
    {
        OtpToken::factory()->create([
            'whatsapp_number' => '628123456789',
            'otp_code' => '123456',
            'used' => true,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $result = OtpToken::valid('628123456789', '123456')->first();

        $this->assertNull($result);
    }

    public function test_scope_valid_does_not_return_wrong_otp()
    {
        OtpToken::factory()->create([
            'whatsapp_number' => '628123456789',
            'otp_code' => '123456',
            'used' => false,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $result = OtpToken::valid('628123456789', '654321')->first();

        $this->assertNull($result);
    }
}
