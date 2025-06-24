<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventContent extends Model
{
    protected $fillable = [
        'event_id',
        'template_type',
        'content',
        'order',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'event_id');
    }

}