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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id('id_mobil');
            $table->bigInteger('id_mitra')->unsigned()->nullable();
            $table->foreign('id_mitra')->references('id_mitra')->on('mitras');
            $table->string('nama_mobil', 30);
            $table->string('tipe_mobil', 30);
            $table->string('jenis_transmisi', 30);
            $table->string('jenis_bahan_bakar', 30);
            $table->string('warna_mobil', 30);
            $table->integer('volume_bahan_bakar');
            $table->boolean('kategori_aset');
            $table->integer('kapasitas_penumpang');
            $table->double('harga_sewa_mobil');
            $table->string('plat_nomor', 30);
            $table->string('nomor_stnk', 30);
            $table->string('status_ketersediaan', 30);
            $table->string('url_foto_mobil');
            $table->string('fasilitas');
            $table->date('mulai_kontrak')->nullable();
            $table->date('selesai_kontrak')->nullable();
            $table->date('tanggal_servis_terakhir');
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
        Schema::dropIfExists('mobils');
    }
};