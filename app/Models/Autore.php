<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autore extends Model
{
    protected $table = 'autores';

    protected $fillable = [
        'nome', 'biografia', 'immagine_profilo', 'anno_nascita'
    ];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function formati()
    {
        return $this->belongsToMany(Formato::class, 'autore_formato');
    }
}