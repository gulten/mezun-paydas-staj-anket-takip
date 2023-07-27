<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnketUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anket_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('anket_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('anket_id')->references('id')->on('anketler');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anket_user');
    }
}
