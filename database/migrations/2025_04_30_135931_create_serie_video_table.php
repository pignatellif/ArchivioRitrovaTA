<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serie_video', function (Blueprint $table) {
            $table->foreignId('serie_id')->constrained('series')->onDelete('cascade');
            $table->foreignId('video_id')->constrained('videos')->onDelete('cascade');
            $table->primary(['serie_id', 'video_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('serie_video');
    }
};