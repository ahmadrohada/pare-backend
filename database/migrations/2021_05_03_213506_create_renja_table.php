<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renja', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('skpd_id');
            $table->integer('periode_id');
            $table->string('nama_skpd');
            $table->string('singkatan_skpd');
            $table->string('nama_kepala_skpd');
            $table->string('nama_admin');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renja');
    }
}
