<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMovielistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movie_lists', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
            $table->uuid('uuid')->index()->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movie_lists', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('uuid');
        });
    }
}
