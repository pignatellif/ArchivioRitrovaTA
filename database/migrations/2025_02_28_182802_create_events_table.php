<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('cover_image')->nullable(); // Aggiunto il campo per la copertina
            $table->timestamps();
        });
    }    
    
    public function down()
    {
        Schema::dropIfExists('events');
    }
    
};
