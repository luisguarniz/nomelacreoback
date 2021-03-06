<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_cards', function (Blueprint $table) {
            $table->uuid("idOrderCard")->primary();
            $table->uuid('roomID')->nullable();
            $table->uuid('idSessionGame')->nullable();
            $table->uuid('idCard')->nullable();
            $table->integer("position")->default("0");
            $table->timestamps();

            $table->foreign('roomID')
            ->references('roomID')->on('rooms')
            ->onDelete('set null');

            $table->foreign('idSessionGame')
            ->references('idSessionGame')->on('session_games')
            ->onDelete('set null');

            $table->foreign('idCard')
            ->references('idCard')->on('cards')
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
        Schema::dropIfExists('order_cards');
    }
}
