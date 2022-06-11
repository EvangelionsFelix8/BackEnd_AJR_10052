<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Api\AuthController@login');


Route::post('role', 'Api\RoleController@store');
Route::get('role', 'Api\RoleController@index');
Route::get('role/{id_role}', 'Api\RoleController@show');
Route::put('role/{id_role}', 'Api\RoleController@update');
Route::delete('role/{id_role}', 'Api\RoleController@destroy');

Route::post('promo', 'Api\PromoController@store');
Route::get('promo', 'Api\PromoController@index');
Route::get('showbystatuspromo', 'Api\PromoController@showByStatus');
Route::get('promo/{id_promo}', 'Api\PromoController@show');
Route::put('promo/{id_promo}', 'Api\PromoController@update');
Route::delete('promo/{id_promo}', 'Api\PromoController@destroy');

Route::post('detailjadwal', 'Api\DetailJadwalController@store');
Route::get('detailjadwal', 'Api\DetailJadwalController@index');
Route::get('detailjadwal_selasa', 'Api\DetailJadwalController@index_selasa');
Route::get('detailjadwal_rabu', 'Api\DetailJadwalController@index_rabu');
Route::get('detailjadwal_kamis', 'Api\DetailJadwalController@index_kamis');
Route::get('detailjadwal_jumat', 'Api\DetailJadwalController@index_jumat');
Route::get('detailjadwal_sabtu', 'Api\DetailJadwalController@index_sabtu');
Route::get('detailjadwal_minggu', 'Api\DetailJadwalController@index_minggu');
// Route::get('detailjadwal', 'Api\DetailJadwalController@getJadwalWithPegawai');
Route::get('detailjadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@show');
Route::put('detailjadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@update');
Route::delete('detailjadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@destroy');

Route::post('jadwal', 'Api\JadwalController@store');
Route::get('jadwal', 'Api\JadwalController@index');
Route::get('jadwal/{id_jadwal}', 'Api\JadwalController@show');
Route::put('jadwal/{id_jadwal}', 'Api\JadwalController@update');
Route::delete('jadwal/{id_jadwal}', 'Api\JadwalController@destroy');

Route::post('pegawai', 'Api\PegawaiController@store');
Route::get('pegawai', 'Api\PegawaiController@index');
Route::get('showbystatuspegawai', 'Api\PegawaiController@showByStatus');
Route::get('pegawai/{id_pegawai}', 'Api\PegawaiController@show');
Route::post('pegawai/{id_pegawai}', 'Api\PegawaiController@update');
Route::delete('pegawai/{id_pegawai}', 'Api\PegawaiController@destroy');

Route::post('driver', 'Api\DriverController@store');
Route::get('driver', 'Api\DriverController@index');
Route::get('getreratadriver', 'Api\DriverController@getreratadriver');
Route::get('getreratadriverbyid/{id_driver}', 'Api\DriverController@getreratadriverbyId');
Route::get('getreratadriverfortable', 'Api\DriverController@getreratadriverfortable');
Route::get('showbystatusketersediaan', 'Api\DriverController@showByStatusKeter');
Route::get('showbyaktifdriver', 'Api\DriverController@showByAktif');
Route::get('driver/{id_driver}', 'Api\DriverController@show');
Route::post('driver/{id_driver}', 'Api\DriverController@update');
Route::put('updatedriver/{id_driver}', 'Api\DriverController@updatedrivermobile');
Route::post('updateberkasdriver/{id_driver}', 'Api\DriverController@updateBerkas');
Route::put('updateketersediaandriver/{id_driver}', 'Api\DriverController@updateStatusKetersediaanbyid');
Route::delete('driver/{id_driver}', 'Api\DriverController@destroy');

Route::post('mitra', 'Api\MitraController@store');
Route::get('mitra', 'Api\MitraController@index');
Route::get('mitrabystatusmitra', 'Api\MitraController@mitraByStatus');
Route::get('mitra/{id_mitra}', 'Api\MitraController@show');
Route::put('mitra/{id_mitra}', 'Api\MitraController@update');
Route::delete('mitra/{id_mitra}', 'Api\MitraController@destroy');

Route::post('mobil', 'Api\MobilController@store');
Route::get('mobil', 'Api\MobilController@index');
Route::get('mobilwithmitra', 'Api\MobilController@indexWithMitra');
Route::get('showbystatusmobil', 'Api\MobilController@showByStatus');
Route::get('getmobilbyexpiredsoon', 'Api\MobilController@getmobilbyexpiredsoon');
Route::get('mobil/{id_mobil}', 'Api\MobilController@show');
Route::post('mobil/{id_mobil}', 'Api\MobilController@update');
Route::delete('mobil/{id_mobil}', 'Api\MobilController@destroy');

Route::post('customer', 'Api\CustomerController@store');
Route::get('customer', 'Api\CustomerController@index');
Route::get('customer/{id_customer}', 'Api\CustomerController@show');
Route::get('countTransaction/{id_customer}', 'Api\CustomerController@countTransaction');
Route::get('countTransactionDone/{id_customer}', 'Api\CustomerController@countTransactionDone');
Route::get('countTransactionBatal/{id_customer}', 'Api\CustomerController@countTransactionBatal');
Route::post('customer/{id_customer}', 'Api\CustomerController@update');
Route::post('updateberkascustomer/{id_customer}', 'Api\CustomerController@updateBerkas');
Route::put('updatepassword/{id_customer}', 'Api\CustomerController@updatePassword');
Route::delete('customer/{id_customer}', 'Api\CustomerController@destroy');

Route::post('transaksi', 'Api\TransaksiController@store');
Route::post('bayartransaksi/{id_transaksi}', 'Api\TransaksiController@pembayaran');
Route::post('bataltransaksi/{id_transaksi}', 'Api\TransaksiController@batal');
Route::post('ratingdriver/{id_transaksi}', 'Api\TransaksiController@addRatingDriver');
Route::post('setStatus/{id_transaksi}', 'Api\TransaksiController@updateStatus');
Route::get('transaksi', 'Api\TransaksiController@index');
// Route::get('getreratadrivertrans', 'Api\TransaksiController@getreratadriver');
Route::get('transaksi/{id_transaksi}', 'Api\TransaksiController@show');
Route::get('showbycustomer/{id_customer}', 'Api\TransaksiController@showbycustomer');
Route::get('showbydriver/{id_driver}', 'Api\TransaksiController@showbydriver');
Route::get('showbycustomerOnProgress/{id_customer}', 'Api\TransaksiController@showbycustomerOnProgress');
// Route::get('countTransaction/{id_transaksi}', 'Api\TransaksiController@countTransaction');
Route::post('transaksi/{id_transaksi}', 'Api\TransaksiController@update');
Route::delete('transaksi/{id_transaksi}', 'Api\TransaksiController@destroy');

Route::get('laporanpenyewaan/{tanggal_mulai}/{tanggal_selesai}', 'Api\LaporanController@LaporanPenyewaanMobil');
Route::get('laporandetailpendapatan/{tanggal_mulai}/{tanggal_selesai}', 'Api\LaporanController@LaporanDetailPendapatan');
Route::get('laporan5driverteratas/{tanggal_mulai}/{tanggal_selesai}', 'Api\LaporanController@Laporan5DriverTransaksiTeratas');
Route::get('Laporanperformadriver/{tanggal_mulai}/{tanggal_selesai}', 'Api\LaporanController@LaporanPerformaDriver');
Route::get('Laporan5terajin/{tanggal_mulai}/{tanggal_selesai}', 'Api\LaporanController@Laporan5CustomerTerbanyak');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });