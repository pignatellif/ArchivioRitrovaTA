<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formato_autore', function (Blueprint $table) {
            $table->unsignedBigInteger('autore_id'); // Colonna autore_id
            $table->string('formato');
            $table->primary(['autore_id', 'formato']); // Chiave primaria composta

            // Definizione della chiave esterna
            $table->foreign('autore_id')
                  ->references('id')
                  ->on('autores') // Nome corretto della tabella di riferimento
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formato_autore');
    }
};