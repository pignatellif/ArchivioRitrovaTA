<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formato extends Model
{
    protected $table = 'formati';
    
    protected $fillable = ['nome', 'descrizione'];

    public function autori()
    {
        return $this->belongsToMany(Autore::class, 'autore_formato');
    }
}