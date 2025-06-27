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

    public function getThumbnailUrlAttribute()
    {
        // Se hai una colonna 'thumbnail_url' che contiene già l'URL assoluto:
        if (!empty($this->attributes['thumbnail_url'])) {
            return $this->attributes['thumbnail_url'];
        }
        // Oppure se salvi solo il nome file su storage pubblico
        if (!empty($this->attributes['thumbnail'])) {
            return asset('storage/' . $this->attributes['thumbnail']);
        }
        // Fallback: se hai youtube_id e vuoi la thumb da YouTube
        if ($this->youtube_id) {
            return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
        }
        return null;
    }
}