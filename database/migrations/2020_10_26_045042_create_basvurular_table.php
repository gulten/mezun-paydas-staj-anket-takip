<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasvurularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basvurular', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('yetkili_id')->nullable();
            $table->unsignedBigInteger('islem_id');
            $table->unsignedBigInteger('firma_id');
            $table->date('baslangic_tarihi');
            $table->date('bitis_tarihi');
            $table->boolean('cumartesi')->default(false);

            $table->enum('basvuru_belge_teslim', ['bekleniyor', 'teslim edildi', 'tarihi geçti'])->default('bekleniyor');
            $table->enum('bitis_belge_teslim', ['bekleniyor','teslim edildi', 'tarihi geçti'])->default('bekleniyor');
            $table->enum('mulakat', ['bekleniyor', 'tarihi geçti', 'tamamlandı'])->default('bekleniyor');

            $table->unsignedBigInteger('ogrenci_anket_id')->nullable();
            $table->unsignedBigInteger('yetkili_anket_id')->nullable();

            $table->smallInteger('sicil_fisi_notu')->nullable();
            $table->smallInteger('dosya_notu')->nullable();
            $table->smallInteger('mulakat_notu')->nullable();
            $table->smallInteger('staj_gun_sayisi')->nullable();
            $table->smallInteger('kabul_edilen_gun_sayisi')->nullable();
            $table->smallInteger('red_edilen_gun_sayisi')->nullable();

            $table->boolean('statu')->default(false);

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('yetkili_id')->references('id')->on('users');

            $table->foreign('islem_id')->references('id')->on('islemler');
            $table->foreign('firma_id')->references('id')->on('firmalar');

            $table->foreign('ogrenci_anket_id')->references('id')->on('anket_user');
            $table->foreign('yetkili_anket_id')->references('id')->on('anket_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('basvurular');
    }
}
