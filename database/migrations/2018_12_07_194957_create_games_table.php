<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_1_id')->unsigned();
            $table->integer('player_2_id')->unsigned();
            $table->integer('score_to_win')->default(10);
            $table->integer('rounds_completed')->default(0);
            $table->boolean('is_finished')->default(false);
            $table->timestamps();

            $table->foreign('player_1_id')->references('id')->on('players');
            $table->foreign('player_2_id')->references('id')->on('players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
