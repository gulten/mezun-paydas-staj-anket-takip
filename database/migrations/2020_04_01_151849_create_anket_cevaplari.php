<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnketCevaplari extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anket_cevaplari', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('anket_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('soru_id');
            $table->string('cevap')->nullable();
            $table->foreign('anket_id')->references('id')->on('anketler');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('soru_id')->references('id')->on('anket_sorulari');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anket_cevaplari');
    }
}
