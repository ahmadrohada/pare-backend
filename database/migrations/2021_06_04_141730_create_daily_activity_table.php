<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->date('workdate');
            $table->string('title');
            $table->string('hasil');
            $table->string('location')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            //$table->json('pegawai')->default('{"id": "","nip": "","nama":"","skpd_id":""}');
            //$table->json('pejabat_penilai')->default('{"id": "","nip": "","nama":"","skpd_id":""}');
            $table->json('attribut')->default('{"isReadOnly": "0","category": "time","calendarId":"1","calendarId":"1"}');
            $table->enum('approval', ['0', '1'])->default('0');


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
        Schema::dropIfExists('daily_activity');
    }
}
