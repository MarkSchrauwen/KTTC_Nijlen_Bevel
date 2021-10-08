<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('team_name');
            $table->string('competition')->enum(["vttl","sporta"]);
            $table->string('season');
            $table->string('competition_number');
            $table->date('competition_date')->nullable();
            $table->time('competition_time')->nullable();
            $table->string('home_team');
            $table->string('visitor_team');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competitions');
    }
}
