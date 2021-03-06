<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->string('id_driver')->primary();
            $table->string('nama_driver');
            $table->string('alamat_driver');
            $table->string('email_driver')->unique();
            $table->string('status_ketersediaan_driver');
            $table->string('status_berkas');
            $table->boolean('isEnglish');
            $table->date('tanggal_lahir_driver');
            $table->string('jenis_kelamin');
            $table->string('no_telp_driver');
            $table->string('url_foto_driver')->nullable();
            $table->string('password');
            $table->double('tarif_sewa_driver');
            $table->string('berkas_bebas_napza')->nullable();
            $table->string('berkas_sim')->nullable();
            $table->string('berkas_sehat_jiwa')->nullable();
            $table->string('berkas_sehat_jasmani')->nullable();
            $table->string('berkas_skck')->nullable();
            $table->double('rerata_rating_driver');
            $table->boolean('isAktif');
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
        Schema::dropIfExists('drivers');
    }
};