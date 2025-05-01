<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = ['nome', 'descrizione'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'serie_video');
    }

    public function durataTotale()
    {
        return $this->videos()->sum('durata_secondi');
    }
}
