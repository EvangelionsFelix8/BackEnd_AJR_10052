<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $drivers = Driver::all();

        if (count($drivers) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $drivers
            ], 200); // return data semua promo dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data promo kosong
    }

    public function show($id_driver)
    {
        $driver = Driver::find($id_driver);

        if (!is_null($driver)) {
            return response([
                'message' => 'Retrieve Driver Success',
                'data' => $driver
            ], 200);
        } // return data detailjadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ], 404); // return message saat data detailjadwal tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'nama_driver' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat_driver' => 'required',
            'email_driver' => 'required|email:rfc,dns|unique:Drivers',
            'status_ketersediaan_driver' => 'required|regex:/^[\pL\s\-]+$/u',
            'status_berkas' => 'required|regex:/^[\pL\s\-]+$/u',
            'isEnglish' => 'required',
            'tanggal_lahir_driver' => 'required',
            'jenis_kelamin' => 'required|regex:/^[\pL\s\-]+$/u',
            'no_telp_driver' => 'required|numeric|starts_with:08',
            'url_foto_driver' => 'required',
            'password' => 'required',
            'tarif_sewa_driver' => 'required|numeric',
            'berkas_bebas_napza' => 'required',
            'berkas_sim' => 'required',
            'berkas_sehat_jiwa' => 'required',
            'berkas_sehat_jasmani' => 'required',
            'berkas_skck' => 'required',
            'rerata_rating_driver' => 'required|numeric',
        ]); //Membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }
        $count = DB::table('drivers')->count() + 1;
        $id_generate = sprintf("%03d", $count);
        $datenow = Carbon::now()->format('dmy');
        $Driver = Driver::create([
            'id_driver' => 'DRV-' . $datenow . $id_generate,
            'nama_driver' => $request->nama_driver,
            'alamat_driver' => $request->alamat_driver,
            'email_driver' => $request->email_driver,
            'status_ketersediaan_driver' => $request->status_ketersediaan_driver,
            'status_berkas' => $request->status_berkas,
            'isEnglish' => $request->isEnglish,
            'tanggal_lahir_driver' => $request->tanggal_lahir_driver,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telp_driver' => $request->no_telp_driver,
            'url_foto_driver' => $request->url_foto_driver,
            'password' => $request->password,
            'tarif_sewa_driver' => $request->tarif_sewa_driver,
            'berkas_bebas_napza' => $request->berkas_bebas_napza,
            'berkas_sim' => $request->berkas_sim,
            'berkas_sehat_jiwa' => $request->berkas_sehat_jiwa,
            'berkas_sehat_jasmani' => $request->berkas_sehat_jasmani,
            'berkas_skck' => $request->berkas_skck,
            'rerata_rating_driver' => $request->rerata_rating_driver
        ]);

        return response([
            'message' => 'Add Driver Success',
            'data' => $Driver
        ], 200); //Return message data Driver baru dalam bentuk JSON
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::find($id);

        if (is_null($driver)) {
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_driver' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat_driver' => 'required',
            'email_driver' => ['required', 'email:rfc,dns', Rule::unique('drivers')->ignore($driver)],
            'status_ketersediaan_driver' => 'required|regex:/^[\pL\s\-]+$/u',
            'status_berkas' => 'required|regex:/^[\pL\s\-]+$/u',
            'isEnglish' => 'required',
            'tanggal_lahir_driver' => 'required',
            'jenis_kelamin' => 'required|regex:/^[\pL\s\-]+$/u',
            'no_telp_driver' => 'required|numeric|starts_with:08',
            'url_foto_driver' => 'required',
            'password' => 'required',
            'tarif_sewa_driver' => 'required|numeric',
            'berkas_bebas_napza' => 'required',
            'berkas_sim' => 'required',
            'berkas_sehat_jiwa' => 'required',
            'berkas_sehat_jasmani' => 'required',
            'berkas_skck' => 'required',
            'rerata_rating_driver' => 'required|numeric',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $driver->nama_driver = $updateData['nama_driver'];
        $driver->alamat_driver = $updateData['alamat_driver'];
        $driver->email_driver = $updateData['email_driver'];
        $driver->status_ketersediaan_driver = $updateData['status_ketersediaan_driver'];
        $driver->status_berkas = $updateData['status_berkas'];
        $driver->isEnglish = $updateData['isEnglish'];
        $driver->tanggal_lahir_driver = $updateData['tanggal_lahir_driver'];
        $driver->jenis_kelamin = $updateData['jenis_kelamin'];
        $driver->no_telp_driver = $updateData['no_telp_driver'];
        $driver->url_foto_driver = $updateData['url_foto_driver'];
        $driver->password = $updateData['password'];
        $driver->tarif_sewa_driver = $updateData['tarif_sewa_driver'];
        $driver->berkas_bebas_napza = $updateData['berkas_bebas_napza'];
        $driver->berkas_sim = $updateData['berkas_sim'];
        $driver->berkas_sehat_jiwa = $updateData['berkas_sehat_jiwa'];
        $driver->berkas_sehat_jasmani = $updateData['berkas_sehat_jasmani'];
        $driver->berkas_skck = $updateData['berkas_skck'];
        $driver->rerata_rating_driver = $updateData['rerata_rating_driver'];

        if ($driver->save()) {
            return response([
                'message' => 'Update Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Update Driver Failed',
            'data' => null,
        ], 400); // return message saat detailjadwal gagal di edit
    }

    public function destroy($id)
    {
        $driver = Driver::find($id);

        if (is_null($driver)) {
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($driver->delete()) {
            return response([
                'message' => 'Delete Driver Success',
                'data' => $driver
            ], 200);
        } // return message saat berhasil menghapus data detailjadwal

        return response([
            'message' => 'Delete Driver Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data detailjadwal
    }
}