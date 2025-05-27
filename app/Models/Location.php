<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'lat', 'lon'];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}