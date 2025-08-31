<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    protected $token;
    protected $phoneNumberId;

    public function __construct()
    {
        $this->token = env('WHATSAPP_TOKEN');
        $this->phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
    }

    public function sendMessage($to, $message)
    {
        $url = "https://graph.facebook.com/v17.0/{$this->phoneNumberId}/messages";

        $response = Http::withToken($this->token)->post($url, [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "text",
            "text" => [
                "body" => $message
            ]
        ]);

        return $response->json();
    }

    public function notifyAdminBooking($booking)
    {
        $adminNumber = env('WHATSAPP_ADMIN_NUMBER');
        $message = "📢 Ada pemesanan baru!\n"
            . "Nama: " . $booking->user->name . "\n"
            . "Jadwal: " . $booking->jadwal->tanggal . " " . $booking->jadwal->jam . "\n"
            . "Kursi: " . $booking->seat_number;

        return $this->sendMessage($adminNumber, $message);
    }
}
