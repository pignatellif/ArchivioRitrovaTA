<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('titolo');
            $table->integer('anno');
            $table->integer('durata_secondi');
            $table->string('formato')->nullable();
            $table->text('descrizione')->nullable();
            $table->string('famiglia')->nullable();
            $table->string('luogo')->nullable();
            $table->string('link_youtube')->nullable();
            $table->unsignedBigInteger('autore_id'); // Chiave esterna

            // Chiave esterna aggiornata
            $table->foreign('autore_id')
                  ->references('id')
                  ->on('autores') // Nome corretto della tabella
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};