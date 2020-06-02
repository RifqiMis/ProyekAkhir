<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJamKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jam_kerja', function (Blueprint $table) {
            $table->id('id_jam_kerja');
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->time('jam_mulai_istirahat');
            $table->time('jam_selesai_istirahat');
            $table->set('hari_kerja',["1","2","3","4","5","6","7"]);
            $table->datetime('tanggal_mulai');
            $table->datetime('tanggal_akhir');
            $table->string('keterangan');
            $table->time('toleransi');
            $table->enum("default", ["y","n"]);
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
        Schema::dropIfExists('jam_kerja');
    }
}
