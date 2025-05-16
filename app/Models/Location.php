<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'lat', 'lon'];

    public $timestamps = true;

    public function videos()
    {
        return $this->hasMany(Video::class); 
    }
}