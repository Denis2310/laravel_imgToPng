<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id');
            $table->string('path');
            $table->integer('to_user');
            $table->string('from_user');
            $table->boolean('is_converted')->default(false);

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
        Schema::dropIfExists('image_users');
    }
}
