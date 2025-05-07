<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityHistory extends Model
{
    protected $fillable = [
        'title',
        'content',
    ];

    public function images()
    {
        return $this->hasMany(CityHistoryImage::class);
    }
}
