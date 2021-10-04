<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShowingsTableAddSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('showings', function (Blueprint $table) {
            $table->foreignId('schedule_id')
                ->nullable()
                ->references('id')
                ->on('schedules')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('showings', function (Blueprint $table) {
            $table->dropColumn('showings');
        });
    }
}
