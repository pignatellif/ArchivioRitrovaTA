<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'titolo', 'anno', 'durata_secondi', 'formato_id',
        'descrizione', 'youtube_id', 'autore_id', 'location_id'
    ];

    public function autore()
    {
        return $this->belongsTo(Autore::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_video');
    }

    public function series()
    {
        return $this->belongsToMany(Serie::class, 'serie_video');
    }

    public function famiglie()
    {
        return $this->belongsToMany(Famiglia::class, 'video_famiglia');
    }

    public function formato()
    {
        return $this->belongsTo(Formato::class);
    }
}