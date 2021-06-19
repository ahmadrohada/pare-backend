<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkpRencanaKinerjaIndikatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skp_rencana_kinerja_indikator', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->integer('rencana_kinerja_id');
            $table->enum('aspek',['','kuantitas','kualitas','waktu','biaya']);
            $table->string('target');
            $table->string('satuan_target');
            $table->string('keterangan_target');
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
        Schema::dropIfExists('skp_rencana_kinerja_indikator');
    }
}
