<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->bigIncrements("resource_id");
            $table->string("name");
            $table->string("description");

            //Estado recurso: 1 disponible, 2 mantenimiento, 3 fuera de servicio
            $table->tinyInteger("status")->default(1);

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('category_id')->on('categories');

            $table->unsignedBigInteger("created_by")->nullable();
            $table->foreign("created_by")->references("user_id")->on("users");

            $table->unsignedBigInteger("updated_by")->nullable();
            $table->foreign("updated_by")->references("user_id")->on("users");

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
        Schema::dropIfExists('resources');
    }
}
