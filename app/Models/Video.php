<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'titolo', 'anno', 'durata_secondi', 'formato',
        'descrizione', 'famiglia', 'luogo', 'link_youtube', 'autore_id'
    ];

    public function autore()
    {
        return $this->belongsTo(Autore::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_video');
    }

    public function serie()
    {
        return $this->belongsToMany(Serie::class, 'serie_video');
    }
}