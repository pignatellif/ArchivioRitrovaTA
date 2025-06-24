<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventi';

    protected $fillable = [
        'titolo', 'start_date', 'end_date', 'descrizione', 'luogo', 'cover_image'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function contents()
    {
        return $this->hasMany(EventContent::class, 'event_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'event_id');
    }
}