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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->bigInteger('id_role')->unsigned();
            $table->foreign('id_role')->references('id_role')->on('roles');
            $table->string('nama_pegawai');
            $table->string('alamat_pegawai');
            $table->string('email_pegawai')->unique();
            $table->date('tanggal_lahir_pegawai');
            $table->string('jenis_kelamin_pegawai', 30);
            $table->string('no_telp_pegawai', 30);
            $table->string('password_pegawai', 30);
            $table->string('url_foto_pegawai');
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
        Schema::dropIfExists('pegawais');
    }
};