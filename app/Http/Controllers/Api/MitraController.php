<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Mitra;

class MitraController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $mitras = Mitra::all();

        if (count($mitras) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mitras
            ], 200); // return data semua mitra dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data mitra kosong
    }

    public function show($id)
    {
        $mitra = Mitra::find($id);

        if (!is_null($mitra)) {
            return response([
                'message' => 'Retrieve Mitra Success',
                'data' => $mitra
            ], 200);
        } // return data mitra yang ditemukan dalam bentuk json

        return response([
            'message' => 'Mitra Not Found',
            'data' => null
        ], 404); // return message saat data mitra tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_mitra' => 'required|regex:/^[\pL\s\-]+$/u',
            'no_ktp_mitra' => 'required|numeric',
            'alamat_mitra' => 'required',
            'no_telp_mitra' => 'required|numeric|starts_with:08',
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);


        $mitra = Mitra::create($storeData);
        return response([
            'message' => 'Add Mitra Success',
            'data' => $mitra
        ], 200); // return data mitra baru dalam bentuk json
    }

    public function destroy($id)
    {
        $mitra = Mitra::find($id);

        if (is_null($mitra)) {
            return response([
                'message' => 'Mitra Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($mitra->delete()) {
            return response([
                'message' => 'Delete Mitra Success',
                'data' => $mitra
            ], 200);
        } // return message saat berhasil menghapus data mitra

        return response([
            'message' => 'Delete Mitra Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data mitra
    }

    public function update(Request $request, $id)
    {
        $mitra = Mitra::find($id);

        if (is_null($mitra)) {
            return response([
                'message' => 'Mitra Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_mitra' => 'required|regex:/^[\pL\s\-]+$/u',
            'no_ktp_mitra' => 'required|numeric',
            'alamat_mitra' => 'required',
            'no_telp_mitra' => 'required|numeric|starts_with:08',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $mitra->nama_mitra = $updateData['nama_mitra'];
        $mitra->no_ktp_mitra = $updateData['no_ktp_mitra'];
        $mitra->alamat_mitra = $updateData['alamat_mitra'];
        $mitra->no_telp_mitra = $updateData['no_telp_mitra'];

        if ($mitra->save()) {
            return response([
                'message' => 'Update Mitra Success',
                'data' => $mitra
            ], 200);
        }

        return response([
            'message' => 'Update Mitra Failed',
            'data' => null,
        ], 400); // return message saat mitra gagal di edit
    }
}