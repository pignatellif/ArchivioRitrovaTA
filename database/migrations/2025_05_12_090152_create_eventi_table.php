<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventi', function (Blueprint $table) {
            $table->id(); // ID univoco
            $table->string('titolo'); // Titolo dell'evento
            $table->date('start_date'); // Data di inizio
            $table->date('end_date')->nullable(); // Data di fine (null se evento di un solo giorno)
            $table->text('descrizione')->nullable(); // Descrizione dell'evento
            $table->string('luogo'); // Luogo di svolgimento
            $table->string('cover_image')->nullable(); // Percorso dell'immagine di copertina
            $table->timestamps(); // Created_at e Updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventi');
    }
}