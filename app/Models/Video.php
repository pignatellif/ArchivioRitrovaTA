<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'titolo',
        'anno',
        'durata_secondi',
        'formato',
        'descrizione',
        'famiglia',
        'link_youtube',
        'autore_id',
        'location_id',
    ];

    public function autore()
    {
        return $this->belongsTo(Autore::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
