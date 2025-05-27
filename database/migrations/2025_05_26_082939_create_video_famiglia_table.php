<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('video_famiglia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained('videos')->onDelete('cascade');
            $table->foreignId('famiglia_id')->constrained('famiglie')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['video_id', 'famiglia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_famiglia');
    }
};
