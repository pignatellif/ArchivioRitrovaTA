<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    /**
     * La tabella associata al model.
     *
     * @var string
     */
    protected $table = 'eventi';

    /**
     * I campi che possono essere assegnati in massa.
     *
     * @var array
     */
    protected $fillable = [
        'titolo',
        'start_date',
        'end_date',
        'descrizione',
        'luogo',
        'cover_image', // Aggiunto cover_image
    ];

    /**
     * Casts per i campi.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}