<?php

namespace Tests\Unit\Services;

use App\Services\WhatsappService;
use App\Models\Booking;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class WhatsappServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $whatsappService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->whatsappService = new WhatsappService();
    }

    public function test_send_message_makes_http_request()
    {
        Http::fake();

        $response = $this->whatsappService->sendMessage('628123456789', 'Test message');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'https://graph.facebook.com/v17.0/') &&
                   str_contains($request->url(), '/messages') &&
                   $request->method() == 'POST' &&
                   $request['messaging_product'] == 'whatsapp' &&
                   $request['to'] == '628123456789' &&
                   $request['type'] == 'text' &&
                   $request['text']['body'] == 'Test message';
        });
    }

    public function test_notify_admin_booking()
    {
        Http::fake();

        $user = User::factory()->create(['name' => 'John Doe', 'whatsapp_number' => '628123456789']);
        $jadwal = Jadwal::factory()->create(['tanggal' => '2023-12-25', 'jam' => '10:00']);
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'jadwal_id' => $jadwal->id,
            'seat_number' => 'A1'
        ]);

        $this->whatsappService->notifyAdminBooking($booking);

        Http::assertSent(function ($request) {
            return str_contains($request['text']['body'], 'Ada pemesanan baru!') &&
                   str_contains($request['text']['body'], 'John Doe') &&
                   str_contains($request['text']['body'], '2023-12-25 10:00') &&
                   str_contains($request['text']['body'], 'A1');
        });
    }
}
