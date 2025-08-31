<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpToken extends Model
{
    protected $fillable = [
        'whatsapp_number',
        'otp_code',
        'expires_at',
        'used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean'
    ];

    public function scopeValid($query, $whatsappNumber, $otpCode)
    {
        return $query->where('whatsapp_number', $whatsappNumber)
                    ->where('otp_code', $otpCode)
                    ->where('used', false)
                    ->where('expires_at', '>', now());
    }
}
