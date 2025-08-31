<?php

namespace App\Services;

use App\Models\OtpToken;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OtpService
{
    /**
     * Generate and send OTP via WhatsApp
     */
    public function sendOtp($whatsappNumber, $isRegistration = false)
    {
        // Untuk registrasi, tidak perlu cek apakah user sudah terdaftar
        if (!$isRegistration) {
            // Check if user exists with this WhatsApp number (untuk reset password)
            $user = User::where('whatsapp_number', $whatsappNumber)->first();

            if (!$user) {
                return ['success' => false, 'message' => 'Nomor WhatsApp tidak terdaftar'];
            }
        }

        // Hapus OTP lama agar tidak menumpuk
        OtpToken::where('whatsapp_number', $whatsappNumber)->delete();

        // Generate 6-digit OTP
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create OTP token (expires in 10 minutes)
        OtpToken::create([
            'whatsapp_number' => $whatsappNumber,
            'otp_code'        => $otpCode,
            'expires_at'      => now()->addMinutes(10),
            'used'            => false
        ]);

        // Kirim OTP via WhatsApp menggunakan WhatsappService
        $whatsappService = app(\App\Services\WhatsappService::class);
        $message = "Kode OTP Anda: {$otpCode}. Berlaku 10 menit.";

        // Untuk development, log saja dulu (komentari jika sudah siap production)
        \Illuminate\Support\Facades\Log::info("OTP untuk {$whatsappNumber}: {$message}");

        // Uncomment baris berikut untuk mengaktifkan WhatsApp API
        // $whatsappService->sendMessage($this->formatNumber($whatsappNumber), $message);

        return ['success' => true, 'message' => 'OTP telah dikirim ke WhatsApp Anda'];
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp($whatsappNumber, $otpCode)
    {
        $otpToken = OtpToken::valid($whatsappNumber, $otpCode)->first();

        if (!$otpToken) {
            return ['success' => false, 'message' => 'Kode OTP tidak valid atau telah kadaluarsa'];
        }

        // Mark OTP as used
        $otpToken->update(['used' => true]);

        return ['success' => true, 'message' => 'OTP berhasil diverifikasi'];
    }

    /**
     * Format nomor WA ke internasional (62...)
     */
    protected function formatNumber($number)
    {
        // Ambil hanya angka
        $number = preg_replace('/[^0-9]/', '', $number);

        // Kalau diawali 0 ubah ke 62
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }

    /**
     * Kirim notifikasi admin jika ada booking baru
     */
    public function sendAdminNotification(Booking $booking)
    {
        $user   = $booking->user;
        $jadwal = $booking->jadwal;

        $message = "📢 *Pesanan Baru!* \n\n" .
            "Nama: {$user->name}\n" .
            "Nomor WA: {$user->whatsapp_number}\n" .
            "Rute/Tujuan: {$jadwal->tujuan}\n" .
            "Tanggal: {$jadwal->tanggal} {$jadwal->jam}\n" .
            "Kursi: {$booking->seat_number}\n\n" .
            "Segera cek sistem admin.";

        // Kirim notifikasi admin menggunakan WhatsappService
        $whatsappService = app(\App\Services\WhatsappService::class);
        $whatsappService->notifyAdminBooking($booking);

        return true;
    }

    /**
     * Clean up expired OTP tokens
     */
    public function cleanupExpiredOtps()
    {
        $deleted = OtpToken::where('expires_at', '<', now())->delete();
        Log::info("Expired OTPs cleaned up: {$deleted}");
        return $deleted;
    }
}
