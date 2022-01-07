<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenjaPeranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renja_peran', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('perjanjian_kinerja_id');
            $table->integer('peran_id');
            $table->integer('jabatan_id');
            $table->string('jabatan_label');
            $table->integer('parent_id');
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
        Schema::dropIfExists('renja_peran');
    }
}
