<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admisis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id');
            $table->unsignedBigInteger('id_disiase');
            $table->string('keluhan');
            $table->text('diagnosa');
            $table->text('tindakan');
            $table->date('tgl_masuk');
            $table->string('tagihan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('pasien_id')->references('id')->on('pasiens');
            $table->foreign('id_disiase')->references('id')->on('disiases');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('admisis');
    }
}
