<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIslemTarihleriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('islem_tarihleri', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('islem_id');
            $table->date('baslangic_tarihi');
            $table->date('bitis_tarihi');
            $table->foreign('islem_id')->references('id')->on('islemler');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('islem_tarihleri');
    }
}
