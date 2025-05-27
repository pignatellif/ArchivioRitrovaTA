<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Famiglia extends Model
{
    protected $table = 'famiglie';
    
    protected $fillable = ['nome', 'descrizione'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_famiglia');
    }
}