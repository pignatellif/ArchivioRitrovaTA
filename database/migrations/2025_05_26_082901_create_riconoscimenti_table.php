<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('riconoscimenti', function (Blueprint $table) {
            $table->id();
            $table->string('titolo');
            $table->string('fonte')->nullable();
            $table->string('url');
            $table->date('data_pubblicazione')->nullable();
            $table->text('estratto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riconoscimenti');
    }
};