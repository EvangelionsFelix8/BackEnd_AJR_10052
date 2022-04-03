<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $transaksis = Transaksi::all();

        if (count($transaksis) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksis
            ], 200); // return data semua promo dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data promo kosong
    }

    public function show($id_driver)
    {
        $transaksi = Transaksi::find($id_driver);

        if (!is_null($transaksi)) {
            return response([
                'message' => 'Retrieve Transaksi Success',
                'data' => $transaksi
            ], 200);
        } // return data detailjadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'Transaksi Not Found',
            'data' => null
        ], 404); // return message saat data detailjadwal tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'id_driver',
            'id_customer' => 'required',
            'id_mobil',
            'id_pegawai' => 'required',
            'id_promo',
            'tanggal_transaksi' => 'required',
            'tanggal_mulai' => 'required|after:tanggal_transaksi',
            'tanggal_pengembalian' => 'after:tanggal_mulai',
            'tanggal_selesai' => 'required|after:tanggal_mulai',
            'status_transaksi' => 'required|regex:/^[\pL\s\-]+$/u',
            'metode_pembayaran',
            'bukti_bayar',
            'total_harga' => 'required|numeric',
            'total_sewa_mobil' => 'required|numeric',
            'total_sewa_driver' => 'required|numeric',
            'total_denda' => 'required|numeric',
            'potongan_promo' => 'required|numeric',
            'rating_driver' => 'numeric',
            'rating_ajr' => 'required|numeric',
        ]); //Membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }
        $count = DB::table('transaksis')->count() + 1;
        $id_generate = sprintf("%03d", $count);
        $datenow = Carbon::now()->format('ymd');
        if (($request->id_driver) === NULL) {
            $kode_pinjam = sprintf("00");
        } else {
            $kode_pinjam = sprintf("01");
        }
        $Transaksi = Transaksi::create([
            'id_transaksi' => 'TRN' . $datenow . $kode_pinjam . '-' . $id_generate,
            'id_driver' => $request->id_driver,
            'id_customer' => $request->id_customer,
            'id_mobil' => $request->id_mobil,
            'id_pegawai' => $request->id_pegawai,
            'id_promo' => $request->id_promo,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status_transaksi' => $request->status_transaksi,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_bayar' => $request->bukti_bayar,
            'total_harga' => $request->total_harga,
            'total_sewa_mobil' => $request->total_sewa_mobil,
            'total_sewa_driver' => $request->total_sewa_driver,
            'total_denda' => $request->total_denda,
            'potongan_promo' => $request->potongan_promo,
            'rating_driver' => $request->rating_driver,
            'rating_ajr' => $request->rating_ajr,

        ]);

        return response([
            'message' => 'Add Transaksi Success',
            'data' => $Transaksi
        ], 200); //Return message data Transaksi baru dalam bentuk JSON
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);

        if (is_null($transaksi)) {
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_driver',
            'id_customer' => 'required',
            'id_mobil',
            'id_pegawai' => 'required',
            'id_promo',
            'tanggal_transaksi' => 'required',
            'tanggal_mulai' => 'required|after:tanggal_transaksi',
            'tanggal_pengembalian' => 'after:tanggal_mulai',
            'tanggal_selesai' => 'required|after:tanggal_mulai',
            'status_transaksi' => 'required|regex:/^[\pL\s\-]+$/u',
            'metode_pembayaran',
            'bukti_bayar',
            'total_harga' => 'required|numeric',
            'total_sewa_mobil' => 'required|numeric',
            'total_sewa_driver' => 'required|numeric',
            'total_denda' => 'required|numeric',
            'potongan_promo' => 'required|numeric',
            'rating_driver' => 'numeric',
            'rating_ajr' => 'required|numeric',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $transaksi->id_driver = $updateData['id_driver'];
        $transaksi->id_customer = $updateData['id_customer'];
        $transaksi->id_mobil = $updateData['id_mobil'];
        $transaksi->id_pegawai = $updateData['id_pegawai'];
        $transaksi->id_promo = $updateData['id_promo'];
        $transaksi->tanggal_transaksi = $updateData['tanggal_transaksi'];
        $transaksi->tanggal_pengembalian = $updateData['tanggal_pengembalian'];
        $transaksi->tanggal_mulai = $updateData['tanggal_mulai'];
        $transaksi->tanggal_selesai = $updateData['tanggal_selesai'];
        $transaksi->status_transaksi = $updateData['status_transaksi'];
        $transaksi->bukti_bayar = $updateData['bukti_bayar'];
        $transaksi->total_harga = $updateData['total_harga'];
        $transaksi->total_sewa_mobil = $updateData['total_sewa_mobil'];
        $transaksi->total_sewa_driver = $updateData['total_sewa_driver'];
        $transaksi->total_denda = $updateData['total_denda'];
        $transaksi->potongan_promo = $updateData['potongan_promo'];
        $transaksi->rating_driver = $updateData['rating_driver'];
        $transaksi->rating_ajr = $updateData['rating_ajr'];

        if ($transaksi->save()) {
            return response([
                'message' => 'Update Transaksi Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Update Transaksi Failed',
            'data' => null,
        ], 400); // return message saat detailjadwal gagal di edit
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);

        if (is_null($transaksi)) {
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($transaksi->delete()) {
            return response([
                'message' => 'Delete Transaksi Success',
                'data' => $transaksi
            ], 200);
        } // return message saat berhasil menghapus data detailjadwal

        return response([
            'message' => 'Delete Transaksi Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data detailjadwal
    }
}