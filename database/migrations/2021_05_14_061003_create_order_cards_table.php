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
            $table->id();
            $table->unsignedBigInteger('idRoom')->nullable();
            $table->unsignedBigInteger('idCard')->nullable();
            $table->integer("position");

            $table->foreign('idRoom')
            ->references('id')->on('rooms')
            ->onDelete('set null');

            $table->foreign('idCard')
            ->references('id')->on('cards')
            ->onDelete('set null');
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
        Schema::dropIfExists('order_cards');
    }
}
