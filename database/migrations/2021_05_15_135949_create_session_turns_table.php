<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionTurnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_turns', function (Blueprint $table) {
            $table->uuid("idTurn")->primary();
            $table->unsignedBigInteger('idUser')->nullable();
            $table->uuid('idSessionGame')->nullable();
            $table->boolean("turn")->default('0');//se coloca 1 si es su turno y cero si no es su turno
            $table->integer("orderTurn")->default('1');// se le asigna un numero aleatorio teniendo en cuenta el total de participantes del juego
            $table->timestamps();

            $table->foreign('idUser')
            ->references('id')->on('users')
            ->onDelete('set null');

            $table->foreign('idSessionGame')
            ->references('idSessionGame')->on('session_games')
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
        Schema::dropIfExists('session_turns');
    }
}
