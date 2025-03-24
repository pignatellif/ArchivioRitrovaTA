<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    
    protected $fillable = ['year', 'duration', 'title', 'description', 'format', 'family', 'location', 'tags', 'yt_link', 'author'];

    public function series()
    {
        return $this->belongsToMany(Series::class, 'series_video');
    }
}