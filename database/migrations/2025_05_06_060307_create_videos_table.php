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
            $table->integer('anno')->nullable();
            $table->integer('durata_secondi');
            $table->string('formato')->nullable();
            $table->text('descrizione')->nullable();
            $table->string('famiglia')->nullable();
            $table->string('link_youtube')->nullable();

            // Foreign key per autore
            $table->foreignId('autore_id')
                ->nullable() 
                ->constrained('autores')
                ->onDelete('set null'); 

            // Foreign key per location
            $table->foreignId('location_id')
                ->constrained('locations')
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
