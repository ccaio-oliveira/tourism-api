<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'whatsapp',
        'latitude',
        'longitude',
    ];

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }
}
