<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRencanaKerjaTahunanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencana_kerja_tahunan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('renja_id');
            $table->string('label');
            $table->enum('level',['s0','s1','s2','s3','s4']);
            $table->enum('type',['tujuan','sasaran','program','kegiatan','subkegiatan']);
            $table->integer('parent_id')->nullable();
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
