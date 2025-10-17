<?php

namespace Tests\Unit\Services;

use App\Services\OtpService;
use App\Models\OtpToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class OtpServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $otpService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->otpService = new OtpService();
    }

    public function test_send_otp_for_registration_success()
    {
        $result = $this->otpService->sendOtp('628123456789', true);

        $this->assertTrue($result['success']);
        $this->assertEquals('OTP telah dikirim ke WhatsApp Anda', $result['message']);

        $this->assertDatabaseHas('otp_tokens', [
            'whatsapp_number' => '628123456789',
            'used' => false,
        ]);
    }

    public function test_send_otp_for_reset_password_user_exists()
    {
        User::factory()->create(['whatsapp_number' => '628123456789']);

        $result = $this->otpService->sendOtp('628123456789', false);

        $this->assertTrue($result['success']);
        $this->assertEquals('OTP telah dikirim ke WhatsApp Anda', $result['message']);
    }

    public function test_send_otp_for_reset_password_user_not_exists()
    {
        $result = $this->otpService->sendOtp('628123456789', false);

        $this->assertFalse($result['success']);
        $this->assertEquals('Nomor WhatsApp tidak terdaftar', $result['message']);
    }

    public function test_verify_otp_success()
    {
        $otpToken = OtpToken::factory()->create([
            'whatsapp_number' => '628123456789',
            'otp_code' => '123456',
            'used' => false,
        ]);

        $result = $this->otpService->verifyOtp('628123456789', '123456');

        $this->assertTrue($result['success']);
        $this->assertEquals('OTP berhasil diverifikasi', $result['message']);

        $this->assertDatabaseHas('otp_tokens', [
            'id' => $otpToken->id,
            'used' => true,
        ]);
    }

    public function test_verify_otp_invalid()
    {
        OtpToken::factory()->create([
            'whatsapp_number' => '628123456789',
            'otp_code' => '123456',
            'used' => false,
        ]);

        $result = $this->otpService->verifyOtp('628123456789', '654321');

        $this->assertFalse($result['success']);
        $this->assertEquals('Kode OTP tidak valid atau telah kadaluarsa', $result['message']);
    }

    public function test_format_number_with_zero_prefix()
    {
        $formatted = $this->invokePrivateMethod($this->otpService, 'formatNumber', ['08123456789']);
        $this->assertEquals('628123456789', $formatted);
    }

    public function test_format_number_without_zero_prefix()
    {
        $formatted = $this->invokePrivateMethod($this->otpService, 'formatNumber', ['8123456789']);
        $this->assertEquals('8123456789', $formatted);
    }

    public function test_cleanup_expired_otps()
    {
        OtpToken::factory()->create(['expires_at' => now()->subMinutes(1)]);
        OtpToken::factory()->create(['expires_at' => now()->addMinutes(1)]);

        $deleted = $this->otpService->cleanupExpiredOtps();

        $this->assertEquals(1, $deleted);
        $this->assertDatabaseCount('otp_tokens', 1);
    }

    private function invokePrivateMethod($object, $method, $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
