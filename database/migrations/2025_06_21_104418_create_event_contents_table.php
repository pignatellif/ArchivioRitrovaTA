<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventContentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('event_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('template_type');
            $table->json('content')->nullable();
            $table->timestamps();
            $table->integer('order')->default(0);
            $table->index('order');

            // ATTENZIONE: cambia il nome della tabella qui sotto se non è 'events'
            $table->foreign('event_id')->references('id')->on('eventi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('event_contents');
    }
}