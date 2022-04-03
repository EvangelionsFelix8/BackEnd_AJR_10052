<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $pegawais = Pegawai::all();

        if (count($pegawais) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawais
            ], 200); // return data semua pegawai dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data pegawai kosong
    }

    public function show($id)
    {
        $pegawai = Pegawai::find($id);

        if (!is_null($pegawai)) {
            return response([
                'message' => 'Retrieve Pegawai Success',
                'data' => $pegawai
            ], 200);
        } // return data pegawai yang ditemukan dalam bentuk json

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ], 404); // return message saat data pegawai tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_pegawai' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat_pegawai' => 'required',
            'email_pegawai' => 'required|email:rfc,dns|unique:Pegawais',
            'tanggal_lahir_pegawai' => 'required',
            'jenis_kelamin_pegawai' => 'required',
            'no_telp_pegawai' => 'required|numeric|starts_with:08',
            'password_pegawai' => 'required',
            'url_foto_pegawai' => 'required',
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);


        $pegawai = Pegawai::create($storeData);
        return response([
            'message' => 'Add Pegawai Success',
            'data' => $pegawai
        ], 200); // return data pegawai baru dalam bentuk json
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::find($id);

        if (is_null($pegawai)) {
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($pegawai->delete()) {
            return response([
                'message' => 'Delete Pegawai Success',
                'data' => $pegawai
            ], 200);
        } // return message saat berhasil menghapus data pegawai

        return response([
            'message' => 'Delete Pegawai Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data pegawai
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);

        if (is_null($pegawai)) {
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_pegawai' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat_pegawai' => 'required',
            'email_pegawai' => ['max:60', 'required', 'email:rfc,dns', Rule::unique('pegawais')->ignore($pegawai)],
            'tanggal_lahir_pegawai' => 'required',
            'jenis_kelamin_pegawai' => 'required',
            'no_telp_pegawai' => 'required|numeric',
            'password_pegawai' => 'required',
            'url_foto_pegawai' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $pegawai->nama_pegawai = $updateData['nama_pegawai'];
        $pegawai->alamat_pegawai = $updateData['alamat_pegawai'];
        $pegawai->email_pegawai = $updateData['email_pegawai'];
        $pegawai->tanggal_lahir_pegawai = $updateData['tanggal_lahir_pegawai'];
        $pegawai->jenis_kelamin_pegawai = $updateData['jenis_kelamin_pegawai'];
        $pegawai->no_telp_pegawai = $updateData['no_telp_pegawai'];
        $pegawai->password_pegawai = $updateData['password_pegawai'];
        $pegawai->url_foto_pegawai = $updateData['url_foto_pegawai'];

        if ($pegawai->save()) {
            return response([
                'message' => 'Update Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Update Pegawai Failed',
            'data' => null,
        ], 400); // return message saat pegawai gagal di edit
    }
}