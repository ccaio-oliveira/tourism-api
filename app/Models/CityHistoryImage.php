<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityHistoryImage extends Model
{
    protected $fillable = [
        'city_history_id',
        'image_path',
    ];

    protected $appends = ['url'];

    public function cityHistory()
    {
        return $this->belongsTo(CityHistory::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
