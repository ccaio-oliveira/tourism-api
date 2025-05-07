<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HotelImage extends Model
{
    protected $fillable = [
        'hotel_id',
        'image_path',
    ];

    protected $appends = ['url'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->image_path);
    }
}
