<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\DetailJadwal;
use Illuminate\Support\Facades\DB;

class DetailJadwalController extends Controller
{
    // method untuk menampilkan semua data product (read)
    // public function index()
    // {
    //     $detailjadwals = DetailJadwal::with(['Pegawai', 'Jadwal'])->get();
    //     // $detailjadwals = DetailJadwal::all();

    //     if (count($detailjadwals) > 0) {
    //         return response([
    //             'message' => 'Retrieve All Success',
    //             'data' => $detailjadwals
    //         ], 200); // return data semua detailjadwal dalam bentuk json
    //     }

    //     return response([
    //         'message' => 'Empty',
    //         'data' => null
    //     ], 400); // return message data detailjadwal kosong
    // }

    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $detailjadwals = DB::table('detail_jadwals')

            ->join('jadwals', 'jadwals.id_jadwal', '=', 'detail_jadwals.id_jadwal')
            ->join('pegawais', 'pegawais.id_pegawai', '=', 'detail_jadwals.id_pegawai')
            ->join('roles', 'roles.id_role', '=', 'pegawais.id_role')
            // ->selectRaw('count(id_pegawai) as count_pegawai')
            ->select('id_detail_jadwal', 'pegawais.id_pegawai', 'jadwals.id_jadwal', 'hari_kerja', 'shift', 'nama_pegawai', 'nama_role')
            // ->groupBy('id_pegawai')
            ->orderBy('hari_kerja', 'desc')->orderBy('shift', 'asc')
            ->get();
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

        $checkUnique = DB::table('detail_jadwals')
            ->select('id_jadwal', 'id_pegawai')
            ->whereRaw("id_jadwal = $request->id_jadwal && id_pegawai= $request->id_pegawai")
            ->get()
            ->first();

        $temp_error = 'Pegawai Sudah Terjadwal pada jadwal ini';
        if ($checkUnique != null) {
            return response(['message' => $temp_error], 400);
        }

        $countShift = DB::table('detail_jadwals')
            ->selectRaw('COUNT(id_pegawai) AS jumlah_shift')
            ->whereRaw("id_pegawai = $request->id_pegawai")
            ->get()
            ->first()
            ->jumlah_shift;

        $temp_count = 'Pegawai Sudah Mencapai Batas Maksimal Shift';
        if ($countShift > 5) {
            return response(['message' => $temp_count], 400);
        }

        $validate = Validator::make($storeData, [
            'id_jadwal' => 'required',
            'id_pegawai' => 'required',
        ], [], [
            'id_jadwal' => 'Hari Kerja dan Shift',
            'id_pegawai' => 'Pegawai dan Jabatan',
        ]); //Membuat rule validasi input

        $err_message = array(array('Pastikan Field Terisi Semuanya'));

        if ($request->id_jadwal === 'null' || $request->id_pegawai === 'null') {
            return response(['message' => $err_message], 400);
        }

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $DetailJadwal = DetailJadwal::create($storeData);
        return response([
            'message' => 'Add detailjadwal Success',
            'data' => $DetailJadwal
        ], 200); //Return message data detailjadwal baru dalam bentuk JSON
    }

    public function destroy($id)
    {
        $detailjadwal = DetailJadwal::find($id);

        if (is_null($detailjadwal)) {
            return response([
                'message' => 'Detail Jadwal Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($detailjadwal->delete()) {
            return response([
                'message' => 'Delete Detail Jadwal Success',
                'data' => $detailjadwal
            ], 200);
        } // return message saat berhasil menghapus data detailjadwal

        return response([
            'message' => 'Delete DetailJadwal Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data detailjadwal
    }

    public function update(Request $request, $id)
    {
        $detailjadwal = DetailJadwal::find($id);

        $checkUnique = DB::table('detail_jadwals')
            ->select('id_jadwal', 'id_pegawai')
            ->whereRaw("id_jadwal = $request->id_jadwal && id_pegawai= $request->id_pegawai")
            ->get()
            ->first();

        $temp_error = 'Pegawai Sudah Terjadwal pada jadwal ini';
        if ($checkUnique != null) {
            return response(['message' => $temp_error], 400);
        }

        if (is_null($detailjadwal)) {
            return response([
                'message' => 'DetailJadwal Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_jadwal' => 'required',
            'id_pegawai' => 'required',
        ], [], [
            'id_jadwal' => 'Hari Kerja dan Shift',
            'id_pegawai' => 'Pegawai dan Jabatan',
        ]);

        $err_message = array(array('Pastikan Field Terisi Semuanya'));

        if ($updateData['id_jadwal'] === 'null' || $updateData['id_pegawai'] === 'null') {
            return response(['message' => $err_message], 400);
        }

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $detailjadwal->id_jadwal = $updateData['id_jadwal'];
        $detailjadwal->id_pegawai = $updateData['id_pegawai'];

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