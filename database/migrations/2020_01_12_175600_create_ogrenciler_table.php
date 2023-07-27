<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOgrencilerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ogrenciler', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->integer('ogrenci_no')->nullable();
            $table->enum('kayit_sekli', ['bilinmiyor', 'YGS','MTOK','DGS','Yatay Geçiş','Mühendislik Tamamlama','Diger'])->default('bilinmiyor');
            $table->date('kayit_yili')->nullable();
            $table->string('telefon',16)->nullable();
            $table->string('adres')->nullable();
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
        Schema::dropIfExists('ogrenciler');
    }
}
