<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_games', function (Blueprint $table) {
            $table->uuid("idStatus")->primary();
            $table->uuid('idSessionGame')->nullable();
            $table->unsignedBigInteger('idUser')->nullable();
            $table->boolean("elegirColor")->default('0');//se coloca 1 si es su turno y cero si no es su turno
            $table->boolean("noMelacreo")->default('0');
            $table->boolean("siMelacreo")->default('0');
            $table->boolean("masoCartas")->default('0');
            $table->boolean("cartasMesa")->default('0');
            $table->boolean("resetGame")->default('0');
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
        Schema::dropIfExists('status_games');
    }
}
