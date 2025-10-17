<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\OtpToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_successful_login()
    {
        $user = User::factory()->create([
            'whatsapp_number' => '628123456789',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        $response = $this->post('/login', [
            'whatsapp_number' => '628123456789',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_failed_login()
    {
        $response = $this->post('/login', [
            'whatsapp_number' => '628123456789',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['whatsapp_number']);
        $this->assertGuest();
    }

    public function test_show_register_page()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_register_with_otp()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'whatsapp_number' => '628123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertRedirect('/register/verify');
        $response->assertSessionHas('registration_data');
        $this->assertDatabaseHas('otp_tokens', ['whatsapp_number' => '628123456789']);
    }

    public function test_show_register_verify_page()
    {
        $this->withSession(['registration_data' => [
            'name' => 'John Doe',
            'whatsapp_number' => '628123456789',
            'password' => 'password123'
        ]]);

        $response = $this->get('/register/verify');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register-verify');
    }

    public function test_verify_register_otp_success()
    {
        $otpToken = OtpToken::factory()->create([
            'whatsapp_number' => '628123456789',
            'otp_code' => '123456',
            'used' => false
        ]);

        $this->withSession(['registration_data' => [
            'name' => 'John Doe',
            'whatsapp_number' => '628123456789',
            'password' => 'password123'
        ]]);

        $response = $this->post('/register/verify', [
            'otp_code' => '123456'
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['whatsapp_number' => '628123456789']);
        $this->assertDatabaseHas('otp_tokens', ['id' => $otpToken->id, 'used' => true]);
    }

    public function test_verify_register_otp_invalid()
    {
        OtpToken::factory()->create([
            'whatsapp_number' => '628123456789',
            'otp_code' => '123456',
            'used' => false
        ]);

        $this->withSession(['registration_data' => [
            'name' => 'John Doe',
            'whatsapp_number' => '628123456789',
            'password' => 'password123'
        ]]);

        $response = $this->post('/register/verify', [
            'otp_code' => '654321'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['otp_code']);
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
        $this->assertGuest();
    }

    public function test_show_forgot_password_form()
    {
        $response = $this->get('/password/reset');

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.email');
    }

    public function test_send_reset_link()
    {
        User::factory()->create(['whatsapp_number' => '628123456789']);

        $response = $this->post('/password/email', [
            'whatsapp_number' => '628123456789'
        ]);

        $response->assertRedirect('/password/reset/confirm');
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('otp_tokens', ['whatsapp_number' => '628123456789']);
    }

    public function test_show_reset_password_form()
    {
        $this->withSession(['reset_whatsapp_number' => '628123456789']);

        $response = $this->get('/password/reset/confirm');

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.reset');
    }

    public function test_reset_password_success()
    {
        $user = User::factory()->create(['whatsapp_number' => '628123456789']);
        OtpToken::factory()->create([
            'whatsapp_number' => '628123456789',
            'otp_code' => '123456',
            'used' => false
        ]);

        $response = $this->post('/password/reset/confirm', [
            'whatsapp_number' => '628123456789',
            'otp_code' => '123456',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('newpassword123', User::where('whatsapp_number', '628123456789')->first()->password));
    }
}
