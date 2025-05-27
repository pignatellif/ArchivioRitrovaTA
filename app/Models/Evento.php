<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventi';

    protected $fillable = [
        'titolo', 'start_date', 'end_date', 'descrizione', 'luogo', 'cover_image'
    ];
}