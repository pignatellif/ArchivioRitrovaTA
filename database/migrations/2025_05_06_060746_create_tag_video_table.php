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
        Schema::create('tag_video', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id'); // Deve essere unsignedBigInteger
            $table->unsignedBigInteger('tag_id');   // Deve essere unsignedBigInteger
            $table->timestamps();

            // Definizione della foreign key con la tabella 'videos'
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            
            // Definizione della foreign key con la tabella 'tags'
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_video');
    }
};