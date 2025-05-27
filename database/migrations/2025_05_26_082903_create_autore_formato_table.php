<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autore_formato', function (Blueprint $table) {
            $table->foreignId('autore_id')->constrained('autores')->onDelete('cascade');
            $table->foreignId('formato_id')->constrained('formati')->onDelete('cascade');
            $table->primary(['autore_id', 'formato_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autore_formato');
    }
};
