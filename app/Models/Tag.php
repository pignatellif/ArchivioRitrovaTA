<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['nome'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'tag_video');
    }
}
