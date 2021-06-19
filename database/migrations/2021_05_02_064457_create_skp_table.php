<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('renja_peran_id');
            $table->enum('jenis_jabatan',['jpt','ja','jf']);
            $table->integer('pegawai_id');
            $table->integer('skpd_id');
            $table->json('pegawai');
            $table->json('pejabat_penilai');
            $table->json('atasan_pejabat_penilai');
            $table->date('periode_penilaian_awal');
            $table->date('periode_penilaian_akhir');
            $table->enum('status',['open','close']);
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
        Schema::dropIfExists('skp');
        Schema::table('skp', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
