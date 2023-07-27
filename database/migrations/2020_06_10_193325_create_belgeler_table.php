<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBelgelerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belgeler', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('belge_id');
            $table->string('belge_adi');
            $table->string('belge_yolu')->nullable();
            $table->text('belge_aciklama')->nullable();
            $table->enum('status', ['active', 'passive'])->default('active');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('belge_id')->references('id')->on('belge_tipleri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belgeler');
    }
}
