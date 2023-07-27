<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBolumDersleriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bolum_dersleri', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('sinif', ['Hazırlık', '1', '2','3','4']);
            $table->enum('donem', ['Güz', 'Bahar']);
            $table->enum('durum', ['Zorunlu', 'Seçmeli'])->default('Zorunlu');
            $table->string('ders_kodu');
            $table->string('ders_adi');
            $table->string('haftalik_ders_saati')->nullable()->comment = 'Teorik + Uygulama/Lab';
            $table->text('amac')->nullable();
            $table->text('icerik')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('bolum_dersleri');
    }
}
