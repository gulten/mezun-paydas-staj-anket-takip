<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsDeneyimiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('is_deneyimi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->date('baslama_tarihi');
            $table->date('ayrilma_tarihi')->nullable();
            $table->string('kurum');
            $table->string('birim');
            $table->string('gorev');
            $table->unsignedInteger('maas');
            $table->unsignedBigInteger('created_id');
            $table->softDeletes();
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
        Schema::dropIfExists('is_deneyimi');
    }
}
