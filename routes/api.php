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

Route::post('role', 'Api\RoleController@store');
Route::get('role', 'Api\RoleController@index');
Route::get('role/{id_role}', 'Api\RoleController@show');
Route::put('role/{id_role}', 'Api\RoleController@update');
Route::delete('role/{id_role}', 'Api\RoleController@destroy');

Route::post('promo', 'Api\PromoController@store');
Route::get('promo', 'Api\PromoController@index');
Route::get('promo/{id_promo}', 'Api\PromoController@show');
Route::put('promo/{id_promo}', 'Api\PromoController@update');
Route::delete('promo/{id_promo}', 'Api\PromoController@destroy');

Route::post('detailjadwal', 'Api\DetailJadwalController@store');
Route::get('detailjadwal', 'Api\DetailJadwalController@index');
// Route::get('detailjadwal/{id_promo}', 'Api\PromoController@show');
// Route::put('detailjadwal/{id_promo}', 'Api\PromoController@update');
// Route::delete('detailjadwal/{id_promo}', 'Api\PromoController@destroy');

Route::post('jadwal', 'Api\JadwalController@store');
Route::get('jadwal', 'Api\JadwalController@index');
Route::get('jadwal/{id_jadwal}', 'Api\JadwalController@show');
Route::put('jadwal/{id_jadwal}', 'Api\JadwalController@update');
Route::delete('jadwal/{id_jadwal}', 'Api\JadwalController@destroy');

Route::post('pegawai', 'Api\PegawaiController@store');
Route::get('pegawai', 'Api\PegawaiController@index');
Route::get('pegawai/{id_pegawai}', 'Api\PegawaiController@show');
Route::put('pegawai/{id_pegawai}', 'Api\PegawaiController@update');
Route::delete('pegawai/{id_pegawai}', 'Api\PegawaiController@destroy');

Route::post('driver', 'Api\DriverController@store');
Route::get('driver', 'Api\DriverController@index');
Route::get('driver/{id_driver}', 'Api\DriverController@show');
Route::put('driver/{id_driver}', 'Api\DriverController@update');
Route::delete('driver/{id_driver}', 'Api\DriverController@destroy');

Route::post('mitra', 'Api\MitraController@store');
Route::get('mitra', 'Api\MitraController@index');
Route::get('mitra/{id_mitra}', 'Api\MitraController@show');
Route::put('mitra/{id_mitra}', 'Api\MitraController@update');
Route::delete('mitra/{id_mitra}', 'Api\MitraController@destroy');

Route::post('mobil', 'Api\MobilController@store');
Route::get('mobil', 'Api\MobilController@index');
Route::get('mobil/{id_mobil}', 'Api\MobilController@show');
Route::put('mobil/{id_mobil}', 'Api\MobilController@update');
Route::delete('mobil/{id_mobil}', 'Api\MobilController@destroy');

Route::post('customer', 'Api\CustomerController@store');
Route::get('customer', 'Api\CustomerController@index');
Route::get('customer/{id_customer}', 'Api\CustomerController@show');
Route::put('customer/{id_customer}', 'Api\CustomerController@update');
Route::delete('customer/{id_customer}', 'Api\CustomerController@destroy');

Route::post('transaksi', 'Api\TransaksiController@store');
Route::get('transaksi', 'Api\TransaksiController@index');
Route::get('transaksi/{id_transaksi}', 'Api\TransaksiController@show');
Route::put('transaksi/{id_transaksi}', 'Api\TransaksiController@update');
Route::delete('transaksi/{id_transaksi}', 'Api\TransaksiController@destroy');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });