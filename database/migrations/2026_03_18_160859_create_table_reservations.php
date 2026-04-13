<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements("reservation_id");

            $table->unsignedBigInteger('space_id');
            $table->foreign('space_id')->references('space_id')->on('spaces');

            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("user_id")->on("users");

            $table->dateTime("date");
            $table->time("start");
            $table->time("end");
            $table->text('notes')->nullable();

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
        Schema::dropIfExists('reservations');
    }
}
