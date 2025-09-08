<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    protected $fillable = [
        'kota_asal',
        'kota_tujuan',
        'jarak_estimasi',
        'harga_tiket',
        'status_rute',
    ];
}
