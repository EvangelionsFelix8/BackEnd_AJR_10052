<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\DetailJadwal;

class DetailJadwalController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $detailjadwals = DetailJadwal::with(['Pegawai', 'Jadwal'])->get();
        // $detailjadwals = DetailJadwal::all();

        if (count($detailjadwals) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detailjadwals
            ], 200); // return data semua detailjadwal dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data detailjadwal kosong
    }

    public function show($id_role)
    {
        $detailjadwal = DetailJadwal::find($id_role);

        if (!is_null($detailjadwal)) {
            return response([
                'message' => 'Retrieve DetailJadwal Success',
                'data' => $detailjadwal
            ], 200);
        } // return data detailjadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'DetailJadwal Not Found',
            'data' => null
        ], 404); // return message saat data detailjadwal tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'nama_role' => 'required|unique:detailjadwals|regex:/^[\pL\s\-]+$/u',
        ]); //Membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }
        $DetailJadwal = DetailJadwal::create($storeData);
        return response([
            'message' => 'Add detailjadwal Success',
            'data' => $DetailJadwal
        ], 200); //Return message data detailjadwal baru dalam bentuk JSON
    }

    public function destroy($id_role)
    {
        $detailjadwal = DetailJadwal::find($id_role);

        if (is_null($detailjadwal)) {
            return response([
                'message' => 'DetailJadwal Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($detailjadwal->delete()) {
            return response([
                'message' => 'Delete DetailJadwal Success',
                'data' => $detailjadwal
            ], 200);
        } // return message saat berhasil menghapus data detailjadwal

        return response([
            'message' => 'Delete DetailJadwal Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data detailjadwal
    }

    public function update(Request $request, $id_role)
    {
        $detailjadwal = DetailJadwal::find($id_role);

        if (is_null($detailjadwal)) {
            return response([
                'message' => 'DetailJadwal Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_role' => ['required', Rule::unique('detailjadwals')->ignore($detailjadwal)],
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $detailjadwal->nama_role = $updateData['nama_role'];

        if ($detailjadwal->save()) {
            return response([
                'message' => 'Update DetailJadwal Success',
                'data' => $detailjadwal
            ], 200);
        }

        return response([
            'message' => 'Update DetailJadwal Failed',
            'data' => null,
        ], 400); // return message saat detailjadwal gagal di edit
    }
}