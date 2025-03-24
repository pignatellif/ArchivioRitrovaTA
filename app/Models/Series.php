<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'duration'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'series_video');
    }
}