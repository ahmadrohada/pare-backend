<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkpRencanaKinerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skp_rencana_kinerja', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('skp_id');
            $table->enum('jenis_kinerja',['','kinerja_utama','kinerja_tambahan']);
            $table->enum('type_kinerja_utama',['','perjanjian_kinerja','direktif','inisiatif_strategis','rencana_aksi']); //wajib bagi JPT dan pimpinan unit kerja mandiri
            $table->enum('penyelarasan_kinerja_utama',['','direct_cascading','non_direct_cascading']); // wajib bagi JA da JF
            $table->string('label');
            $table->integer('parent_id')->nullable();
            $table->enum('revisi',['0','1'])->default(0); //untuk mengetahui apakah rencana kinerja ini merupakan perubahan atau bukan
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
        Schema::dropIfExists('skp_rencana_kinerja');
    }
}
