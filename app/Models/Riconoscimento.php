<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Riconoscimento extends Model
{
    protected $table = 'riconoscimenti';

    protected $fillable = [
        'titolo',
        'fonte',
        'url',
        'data_pubblicazione',
        'estratto',
    ];

    protected $dates = [
        'data_pubblicazione',
        'created_at',
        'updated_at',
    ];
}