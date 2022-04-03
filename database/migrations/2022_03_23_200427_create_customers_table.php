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
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id_customer')->primary();
            $table->string('nama_customer');
            $table->string('alamat_customer');
            $table->date('tanggal_lahir_customer');
            $table->string('jenis_kelamin');
            $table->string('email_customer')->unique();
            $table->string('password');
            $table->string('no_telp_customer', 30);
            $table->string('berkas_customer');
            $table->string('status_berkas');
            $table->string('no_tanda_pengenal');
            $table->string('no_sim');
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
        Schema::dropIfExists('customers');
    }
};