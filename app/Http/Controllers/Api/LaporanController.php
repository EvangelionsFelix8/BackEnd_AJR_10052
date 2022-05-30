<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function LaporanPenyewaanMobil($tanggalawal, $tanggalakhir)
    {
        $data = DB::select("SELECT tipe_mobil, nama_mobil, COUNT(id_mobil) as jumlah_peminjaman, SUM(total_sewa_mobil) AS pendapatan 
            FROM mobils JOIN transaksis USING(id_mobil) 
            WHERE tanggal_transaksi BETWEEN '$tanggalawal' AND '$tanggalakhir'  
            GROUP BY id_mobil, nama_mobil, tipe_mobil
            ORDER BY pendapatan DESC
        ");

        if (count($data) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $data
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Transaksi kosong
    }

    public function LaporanDetailPendapatan($tanggalawal, $tanggalakhir)
    {

        $data = DB::select(
            "SELECT nama_customer, nama_mobil, 
            (CASE WHEN id_driver is NOT NULL THEN 'Peminjaman Mobil + Driver' 
            ELSE 'Peminjaman Mobil' END) AS Jenis_Transaksi,
            COUNT(id_customer) AS jumlah_Transaksi,SUM(total_harga) AS pendapatan
            FROM customers JOIN transaksis USING(id_customer) 
            JOIN mobils USING (id_mobil) 
            WHERE tanggal_transaksi BETWEEN '$tanggalawal' and '$tanggalakhir'
            AND status_transaksi = 'Sudah lunas (Selesai)'
            GROUP BY id_customer, nama_mobil, nama_customer, Jenis_Transaksi
            ORDER BY pendapatan DESC;"
        );

        if (count($data) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $data
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Transaksi kosong
    }

    public function Laporan5DriverTransaksiTeratas($tanggalawal, $tanggalakhir)
    {

        $data = DB::select(
            "SELECT id_driver, nama_driver, COUNT(id_driver) AS jumlah_Transaksi 
            FROM drivers JOIN transaksis USING (id_driver) 
            WHERE tanggal_transaksi between '$tanggalawal' and '$tanggalakhir' 
            GROUP BY id_driver, nama_driver ORDER BY (jumlah_Transaksi) DESC LIMIT 5"
        );

        if (count($data) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $data
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Transaksi kosong
    }

    public function LaporanPerformaDriver($tanggalawal, $tanggalakhir)
    {

        $data = DB::select(
            "SELECT id_driver, nama_driver, COUNT(id_driver) AS jumlah_Transaksi, 
            (SUM(rating_driver)/COUNT(id_driver)) AS rerata_rating_driver 
            FROM drivers JOIN transaksis USING(id_driver) 
            WHERE tanggal_transaksi between '$tanggalawal' and '$tanggalakhir'
            AND rating_driver IS NOT NULL
            GROUP BY id_driver, nama_driver
            ORDER BY jumlah_Transaksi DESC LIMIT 5"
        );

        if (count($data) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $data
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Transaksi kosong
    }

    public function Laporan5CustomerTerbanyak($tanggalawal, $tanggalakhir)
    {

        $data = DB::select(
            "SELECT nama_customer, COUNT(id_customer) AS jumlah_Transaksi 
            FROM transaksis JOIN customers USING (id_customer) 
            WHERE tanggal_transaksi between '$tanggalawal' and '$tanggalakhir'
            AND status_transaksi = 'Sudah lunas (Selesai)'
            GROUP BY id_customer, nama_customer ORDER BY jumlah_Transaksi DESC LIMIT 5"
        );

        if (count($data) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $data
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Transaksi kosong
    }
}