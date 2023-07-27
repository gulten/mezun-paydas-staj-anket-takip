<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBolumeOneriler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bolume_oneriler', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ders_id');
            $table->unsignedBigInteger('user_id');
            $table->text('amaca_yonelik')->nullable();
            $table->text('icerige_yonelik')->nullable();
            $table->text('ders_yariyilina_saatine_yonelik')->nullable();
            $table->text('diger')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ders_id')->references('id')->on('bolum_dersleri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bolume_oneriler');
    }
}
