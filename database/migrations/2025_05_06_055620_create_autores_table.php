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
        Schema::create('autores', function (Blueprint $table) {
            $table->id(); // ID primario
            $table->string('nome'); // Nome completo dell'autore
            $table->text('biografia')->nullable(); // Descrizione breve
            $table->string('immagine_profilo')->nullable(); // Percorso dell'immagine
            $table->integer('anno_nascita')->nullable(); // Solo se ti serve
            $table->timestamps(); // created_at e updated_at
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autores');
    }
};
