<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->string('id_transaksi')->primary();

            $table->string('id_driver')->nullable();
            $table->foreign('id_driver')->references('id_driver')->on('drivers');

            $table->string('id_customer');
            $table->foreign('id_customer')->references('id_customer')->on('customers');

            $table->bigInteger('id_mobil')->unsigned();
            $table->foreign('id_mobil')->references('id_mobil')->on('mobils');

            $table->bigInteger('id_pegawai')->unsigned()->nullable();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais');

            $table->bigInteger('id_promo')->unsigned()->nullable();
            $table->foreign('id_promo')->references('id_promo')->on('promos');

            $table->dateTime('tanggal_transaksi');
            $table->dateTime('tanggal_pengembalian')->nullable();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('status_transaksi');
            $table->string('metode_pembayaran')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->double('total_harga', 15, 2);
            $table->double('total_sewa_mobil');
            $table->double('total_sewa_driver');
            $table->double('total_denda')->nullable();
            $table->double('potongan_promo')->nullable();
            $table->integer('rating_driver')->nullable();
            $table->integer('rating_ajr')->nullable();
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
        Schema::dropIfExists('transaksis');
    }
};