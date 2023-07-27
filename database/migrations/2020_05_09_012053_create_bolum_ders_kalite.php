<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBolumDersKalite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bolum_ders_kalite', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ders_id');
            $table->unsignedBigInteger('user_id');
            $table->smallInteger('kalite');
            $table->text('sebep')->nullable();
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
        Schema::dropIfExists('bolum_ders_kalite');
    }
}
