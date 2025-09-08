<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = ['tujuan', 'tanggal', 'jam', 'harga', 'rute_id', 'mobil_id'];

    public function rute()
    {
        return $this->belongsTo(Rute::class);
    }

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
