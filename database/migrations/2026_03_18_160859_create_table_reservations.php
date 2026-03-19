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

            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("user_id")->on("users");

            $table->unsignedBigInteger("resource_id");
            $table->foreign("resource_id")->references("resource_id")->on("resources");

            $table->dateTime("date");
            $table->time("begin");
            $table->time("end");

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
