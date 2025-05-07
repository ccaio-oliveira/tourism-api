<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TouristAttractionImage extends Model
{
    protected $fillable = [
        'tourist_attraction_id',
        'image_path',
    ];

    protected $appends = ['url'];

    public function touristAttraction()
    {
        return $this->belongsTo(TouristAttraction::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->image_path);
    }
}
