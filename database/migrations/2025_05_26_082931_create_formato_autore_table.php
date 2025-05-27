<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('formato_autore', function (Blueprint $table) {
            $table->foreignId('autore_id')->constrained('autores')->onDelete('cascade');
            $table->string('formato');
            $table->primary(['autore_id', 'formato']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formato_autore');
    }
};