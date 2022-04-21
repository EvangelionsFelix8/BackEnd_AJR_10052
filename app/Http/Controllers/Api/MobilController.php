<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Mobil;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Api\SYSDATETIME;
use COM;

class MobilController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        // $mobils = Mobil::all();
        $from = Carbon::now()->format('ymd');
        $mobils = Mobil::selectRaw("*, DATEDIFF(mobils.selesai_kontrak, $from) as sisa_hari")->get();

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

    public function indexWithMitra()
    {
        $mobils = DB::table('mobils')
            ->join('mitras', 'mitras.id_mitra', '=', 'mobils.id_mitra')->get();

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

    public function getmobilbyexpiredsoon()
    {
        $from = Carbon::now()->format('ymd');
        // $mobils = Mobil::selectRaw("mobils.url_foto_mobil, mobils.nama_mobil, mitras.nama_mitra, DATEDIFF(mobils.selesai_kontrak, $from) as sisa_hari")
        //     ->join('mitras', 'mitras.id_mitra', '=', 'mobils.id_mitra')
        //     ->whereRaw("DATEDIFF(mobils.selesai_kontrak, $from) < 30")
        //     ->where('mobils.id_mitra', '!=', 'NULL')
        //     ->get();

        $mobils = Mobil::selectRaw("*, DATEDIFF(mobils.selesai_kontrak, $from) as sisa_hari")
            ->join('mitras', 'mitras.id_mitra', '=', 'mobils.id_mitra')
            ->whereRaw("DATEDIFF(mobils.selesai_kontrak, $from) < 30")
            ->where('mobils.id_mitra', '!=', 'NULL')
            ->get();

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

    public function showByStatus()
    {
        $mobils = Mobil::where('status_ketersediaan', 'Tersedia')->get();

        if (count($mobils) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mobils
            ], 200); // return data semua mobil dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);  // return message saat data mobil tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_mitra',
            'nama_mobil' => 'required',
            'tipe_mobil' => 'required',
            'jenis_transmisi' => 'required',
            'jenis_bahan_bakar' => 'required',
            'warna_mobil' => 'required',
            'volume_bahan_bakar' => 'required|numeric',
            'kapasitas_penumpang' => 'required|numeric',
            'harga_sewa_mobil' => 'required|numeric',
            'plat_nomor' => 'required',
            'nomor_stnk' => 'required',
            'status_ketersediaan',
            'url_foto_mobil' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'fasilitas' => 'required',
            // 'mulai_kontrak' => 'required',
            // 'selesai_kontrak' => 'required',
            'tanggal_servis_terakhir' => 'required',
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if (($request->id_mitra) === NULL) {
            $jenis_aset = sprintf("0");
        } else {
            $jenis_aset = sprintf("1");
        }
        $fotoMobil = $request->url_foto_mobil->store('foto_mobil', ['disk' => 'public']);
        $mobil = Mobil::create([
            'id_mitra' => $request->id_mitra,
            'nama_mobil' => $request->nama_mobil,
            'tipe_mobil' => $request->tipe_mobil,
            'jenis_transmisi' => $request->jenis_transmisi,
            'jenis_bahan_bakar' => $request->jenis_bahan_bakar,
            'warna_mobil' => $request->warna_mobil,
            'volume_bahan_bakar' => $request->volume_bahan_bakar,
            'kategori_aset' => $jenis_aset,
            'kapasitas_penumpang' => $request->kapasitas_penumpang,
            'harga_sewa_mobil' => $request->harga_sewa_mobil,
            'plat_nomor' => $request->plat_nomor,
            'nomor_stnk' => $request->nomor_stnk,
            'status_ketersediaan' => $request->status_ketersediaan,
            'url_foto_mobil' => $fotoMobil,
            'fasilitas' => $request->fasilitas,
            'mulai_kontrak' => $request->mulai_kontrak,
            'selesai_kontrak' => $request->selesai_kontrak,
            'tanggal_servis_terakhir' => $request->tanggal_servis_terakhir,
        ]);
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
            // 'url_foto_mobil' => 'required',
            'fasilitas' => 'required',
            // 'mulai_kontrak' => 'required',
            // 'selesai_kontrak' => 'required',
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
        if (isset($request->url_foto_mobil)) {
            $fotoMobil = $request->url_foto_mobil->store('foto_mobil', ['disk' => 'public']);
            $mobil->url_foto_mobil = $fotoMobil;
        };
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