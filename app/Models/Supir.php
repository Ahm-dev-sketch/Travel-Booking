<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supir extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'telepon',
        'mobil_id',
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }
}
