<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Jadwal;

class JadwalController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $jadwals = Jadwal::all();

        if (count($jadwals) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwals
            ], 200); // return data semua jadwal dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data jadwal kosong
    }

    public function show($id)
    {
        $jadwal = Jadwal::find($id);

        if (!is_null($jadwal)) {
            return response([
                'message' => 'Retrieve Jadwal Success',
                'data' => $jadwal
            ], 200);
        } // return data jadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'Jadwal Not Found',
            'data' => null
        ], 404); // return message saat data jadwal tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'hari_kerja' => 'required',
            'shift' => 'required',
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);


        $jadwal = Jadwal::create($storeData);
        return response([
            'message' => 'Add Jadwal Success',
            'data' => $jadwal
        ], 200); // return data jadwal baru dalam bentuk json
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);

        if (is_null($jadwal)) {
            return response([
                'message' => 'Jadwal Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($jadwal->delete()) {
            return response([
                'message' => 'Delete Jadwal Success',
                'data' => $jadwal
            ], 200);
        } // return message saat berhasil menghapus data jadwal

        return response([
            'message' => 'Delete Jadwal Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data jadwal
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::find($id);

        if (is_null($jadwal)) {
            return response([
                'message' => 'Jadwal Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'hari_kerja' => ['required', Rule::unique('jadwals')->ignore($jadwal)],
            'shift' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $jadwal->hari_kerja = $updateData['hari_kerja'];
        $jadwal->shift = $updateData['shift'];

        if ($jadwal->save()) {
            return response([
                'message' => 'Update Jadwal Success',
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Update Jadwal Failed',
            'data' => null,
        ], 400); // return message saat jadwal gagal di edit
    }
}