<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();  // ID univoco
            $table->year('year');  // Anno
            $table->integer('duration');  // Durata in secondi
            $table->string('title');  // Titolo
            $table->string('format');  // Formato (es. mp4, mkv)
            $table->text('description');  // Descrizione
            $table->string('family');  // Famiglia (es. fondo)
            $table->string('location');  // Luogo
            $table->string('tags')->nullable();  // Tag separati da virgole
            $table->string('yt_link');  // Link YouTube
            $table->string('author');  // Autore
            $table->timestamps();  // Created_at e updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
}

