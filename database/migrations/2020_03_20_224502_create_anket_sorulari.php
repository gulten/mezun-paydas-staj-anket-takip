<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnketSorulari extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anket_sorulari', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('anket_id');
            $table->string('soru_tipi');
            $table->string('soru');
            $table->text('detay')->nullable();
            $table->boolean('required')->default(false);
            $table->integer('sira');
            $table->softDeletes();
            $table->foreign('anket_id')->references('id')->on('anketler');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anket_sorulari');
    }
}
