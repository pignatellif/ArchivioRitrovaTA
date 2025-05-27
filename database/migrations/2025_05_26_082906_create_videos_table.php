<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('titolo');
            $table->integer('anno')->nullable();
            $table->integer('durata_secondi');
            $table->text('descrizione')->nullable();
            $table->string('youtube_id')->nullable();

            // Foreign key per autore
            $table->foreignId('autore_id')
                ->nullable() 
                ->constrained('autores')
                ->onDelete('set null'); 

            // Foreign key per location
            $table->foreignId('location_id')
                ->constrained('locations')
                ->onDelete('cascade');

            // Foreign key per formato
            $table->foreignId('formato_id')
                ->nullable()
                ->constrained('formati')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};