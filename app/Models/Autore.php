<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autore extends Model
{
    use HasFactory;

    // Specifica il nuovo nome della tabella
    protected $table = 'autores';

    protected $fillable = ['nome', 'anno_nascita', 'biografia', 'immagine_profilo'];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function formati()
    {
        return $this->belongsToMany(Formato::class, 'formato_autore', 'autore_id', 'formato');
    }
}