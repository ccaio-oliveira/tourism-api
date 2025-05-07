<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RestaurantImage extends Model
{
    protected $fillable = [
        'restaurant_id',
        'image_path',
    ];

    protected $appends = ['url'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->image_path);
    }
}
