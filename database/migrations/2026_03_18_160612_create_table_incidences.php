<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableIncidences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidences', function (Blueprint $table) {
            $table->bigIncrements("incidence_id");

            $table->unsignedBigInteger("resource_id");
            $table->foreign("resource_id")->references("resource_id")->on("resources");

            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("user_id")->on("users");

            $table->unsignedBigInteger("created_by")->nullable();
            $table->foreign("created_by")->references("user_id")->on("users");

            $table->unsignedBigInteger("updated_by")->nullable();
            $table->foreign("updated_by")->references("user_id")->on("users");

            $table->dateTime("date_incidence");
            $table->string("description");
            $table->boolean("status");

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
        Schema::dropIfExists('incidences');
    }
}
