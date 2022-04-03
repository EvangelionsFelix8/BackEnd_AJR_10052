<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Mobil;

class MobilController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $mobils = Mobil::all();

        if (count($mobils) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mobils
            ], 200); // return data semua mobil dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data mobil kosong
    }

    public function show($id)
    {
        $mobil = Mobil::find($id);

        if (!is_null($mobil)) {
            return response([
                'message' => 'Retrieve Mobil Success',
                'data' => $mobil
            ], 200);
        } // return data mobil yang ditemukan dalam bentuk json

        return response([
            'message' => 'Mobil Not Found',
            'data' => null
        ], 404); // return message saat data mobil tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_mitra' => 'required',
            'nama_mobil' => 'required',
            'tipe_mobil' => 'required',
            'jenis_transmisi' => 'required',
            'jenis_bahan_bakar' => 'required',
            'warna_mobil' => 'required',
            'volume_bahan_bakar' => 'required|numeric',
            'kategori_aset' => 'required',
            'kapasitas_penumpang' => 'required|numeric',
            'harga_sewa_mobil' => 'required|numeric',
            'plat_nomor' => 'required',
            'nomor_stnk' => 'required',
            'status_ketersediaan' => 'required',
            'url_foto_mobil' => 'required',
            'fasilitas' => 'required',
            'mulai_kontrak' => 'required',
            'selesai_kontrak' => 'required',
            'tanggal_servis_terakhir' => 'required',
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);


        $mobil = Mobil::create($storeData);
        return response([
            'message' => 'Add Mobil Success',
            'data' => $mobil
        ], 200); // return data mobil baru dalam bentuk json
    }

    public function destroy($id)
    {
        $mobil = Mobil::find($id);

        if (is_null($mobil)) {
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($mobil->delete()) {
            return response([
                'message' => 'Delete Mobil Success',
                'data' => $mobil
            ], 200);
        } // return message saat berhasil menghapus data mobil

        return response([
            'message' => 'Delete Mobil Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data mobil
    }

    public function update(Request $request, $id)
    {
        $mobil = Mobil::find($id);

        if (is_null($mobil)) {
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_mitra' => 'required',
            'nama_mobil' => 'required',
            'tipe_mobil' => 'required',
            'jenis_transmisi' => 'required',
            'jenis_bahan_bakar' => 'required',
            'warna_mobil' => 'required',
            'volume_bahan_bakar' => 'required|numeric',
            'kategori_aset' => 'required',
            'kapasitas_penumpang' => 'required|numeric',
            'harga_sewa_mobil' => 'required|numeric',
            'plat_nomor' => 'required',
            'nomor_stnk' => 'required',
            'status_ketersediaan' => 'required',
            'url_foto_mobil' => 'required',
            'fasilitas' => 'required',
            'mulai_kontrak' => 'required',
            'selesai_kontrak' => 'required',
            'tanggal_servis_terakhir' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $mobil->id_mitra = $updateData['id_mitra'];
        $mobil->nama_mobil = $updateData['nama_mobil'];
        $mobil->tipe_mobil = $updateData['tipe_mobil'];
        $mobil->jenis_transmisi = $updateData['jenis_transmisi'];
        $mobil->jenis_bahan_bakar = $updateData['jenis_bahan_bakar'];
        $mobil->warna_mobil = $updateData['warna_mobil'];
        $mobil->volume_bahan_bakar = $updateData['volume_bahan_bakar'];
        $mobil->kategori_aset = $updateData['kategori_aset'];
        $mobil->kapasitas_penumpang = $updateData['kapasitas_penumpang'];
        $mobil->harga_sewa_mobil = $updateData['harga_sewa_mobil'];
        $mobil->plat_nomor = $updateData['plat_nomor'];
        $mobil->nomor_stnk = $updateData['nomor_stnk'];
        $mobil->status_ketersediaan = $updateData['status_ketersediaan'];
        $mobil->url_foto_mobil = $updateData['url_foto_mobil'];
        $mobil->fasilitas = $updateData['fasilitas'];
        $mobil->mulai_kontrak = $updateData['mulai_kontrak'];
        $mobil->selesai_kontrak = $updateData['selesai_kontrak'];
        $mobil->tanggal_servis_terakhir = $updateData['tanggal_servis_terakhir'];

        if ($mobil->save()) {
            return response([
                'message' => 'Update Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Update Mobil Failed',
            'data' => null,
        ], 400); // return message saat mobil gagal di edit
    }
}