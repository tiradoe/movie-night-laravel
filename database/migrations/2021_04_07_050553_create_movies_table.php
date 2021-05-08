<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('title');
            $table->string('imdb_id')->unique();
            $table->year('year');
            $table->string('rated');
            $table->string('genre');
            $table->string('director');
            $table->string('actors');
            $table->text('plot');
            $table->string('poster');
            $table->boolean('isGood')->nullable();
            $table->dateTime('lastWatched')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
