<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->uuid("idTurn")->primary();
            $table->uuid('roomID')->nullable();
            $table->unsignedBigInteger('idUser')->nullable();
            $table->integer("score")->default('0');
            $table->boolean("isActive")->default('0');
            $table->timestamps();

            $table->foreign('idUser')
            ->references('id')->on('users')
            ->onDelete('set null');

            $table->foreign('roomID')
            ->references('roomID')->on('rooms')
            ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
