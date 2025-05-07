<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'message',
        'file',
        'line',
        'trace',
        'url',
        'input'
    ];
}
