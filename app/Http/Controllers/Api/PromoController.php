<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Promo;

class PromoController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $promos = Promo::all();

        if (count($promos) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $promos
            ], 200); // return data semua promo dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data promo kosong
    }

    // method untuk menampilkan semua data product (read)
    public function showByStatus()
    {
        $promos = Promo::where('status_promo', 'Aktif')->get();

        if (count($promos) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $promos
            ], 200); // return data semua promo dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data promo kosong
    }

    public function show($id)
    {
        $promo = Promo::find($id);

        if (!is_null($promo)) {
            return response([
                'message' => 'Retrieve Promo Success',
                'data' => $promo
            ], 200);
        } // return data promo yang ditemukan dalam bentuk json

        return response([
            'message' => 'Promo Not Found',
            'data' => null
        ], 404); // return message saat data promo tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'kode_promo' => 'unique:promos',
            'jenis_promo' => 'required',
            'besar_diskon_promo' => 'required|numeric',
            'status_promo' => 'required',
            'keterangan' => 'required'
        ], [], [
            'kode_promo' => 'Kode Promo',
            'jenis_promo' => 'Alamat Pegawai',
            'besar_diskon_promo' => 'Besar Diskon',
            'status_promo' => 'Status Promo',
            'keterangan' => 'Keterangan'
        ]); // membuat rule validasi input

        $err_message = array(array('Pastikan Field Terisi Semuanya'));

        if (
            $request->keterangan === 'null' || $request->kode_promo === 'null' || $request->jenis_promo === 'null' ||
            $request->besar_diskon_promo === 'null' || $request->status_promo === 'null'
        ) {
            return response(['message' => $err_message], 400);
        }

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $promo = Promo::create($storeData);
        return response([
            'message' => 'Add Promo Success',
            'data' => $promo
        ], 200); // return data promo baru dalam bentuk json
    }

    public function destroy($id)
    {
        $promo = Promo::find($id);

        if (is_null($promo)) {
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($promo->delete()) {
            return response([
                'message' => 'Delete Promo Success',
                'data' => $promo
            ], 200);
        } // return message saat berhasil menghapus data promo

        return response([
            'message' => 'Delete Promo Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data promo
    }

    public function update(Request $request, $id)
    {
        $promo = Promo::find($id);

        if (is_null($promo)) {
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'kode_promo' => ['max:60', 'required', Rule::unique('promos')->ignore($promo)],
            'jenis_promo' => 'required',
            'besar_diskon_promo' => 'required|numeric',
            'status_promo' => 'required',
            'keterangan' => 'required'
        ], [], [
            'kode_promo' => 'Kode Promo',
            'jenis_promo' => 'Alamat Pegawai',
            'besar_diskon_promo' => 'Besar Diskon',
            'status_promo' => 'Status Promo',
            'keterangan' => 'Keterangan'
        ]);

        $err_message = array(array('Pastikan Field Terisi Semuanya'));

        if (
            $updateData['kode_promo'] === 'null' || $updateData['jenis_promo'] === 'null' || $updateData['besar_diskon_promo'] === null ||
            $updateData['kode_promo'] === 'null' || $updateData['keterangan'] === 'null'
        ) {
            return response(['message' => $err_message], 400);
        }

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $promo->kode_promo = $updateData['kode_promo'];
        $promo->jenis_promo = $updateData['jenis_promo'];
        $promo->besar_diskon_promo = $updateData['besar_diskon_promo'];
        $promo->status_promo = $updateData['status_promo'];
        $promo->keterangan = $updateData['keterangan'];

        if ($promo->save()) {
            return response([
                'message' => 'Update Promo Success',
                'data' => $promo
            ], 200);
        }

        return response([
            'message' => 'Update Promo Failed',
            'data' => null,
        ], 400); // return message saat promo gagal di edit
    }
}