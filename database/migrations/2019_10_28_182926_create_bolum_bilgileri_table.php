<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBolumBilgileriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bolum_bilgileri', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('universite_adi',50);
            $table->string('fakulte_adi',50);
            $table->string('adi',50);
            $table->date('kurulus_yili');
            $table->date('faaliyet_baslangic_tarihi');
            $table->string('adres',100);
            $table->string('telefon',16);
            $table->string('email',50);
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
        Schema::dropIfExists('bolum_bilgileri');
    }
}
