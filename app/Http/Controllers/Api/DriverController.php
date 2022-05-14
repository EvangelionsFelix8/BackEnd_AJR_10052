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
        // $drivers = Driver::all()->selectRaw("ROUND(AVG(transaksis.rating_driver), 2) as rata_rating")
        //     ->leftJoin('transaksis', 'transaksis.id_driver', '=', 'drivers.id_driver')
        //     ->get();

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

    public function getreratadriverfortable()
    {
        $drivers = Driver::selectRaw("drivers.id_driver, ROUND(AVG(transaksis.rating_driver), 2) as rata_rating")
            ->leftJoin('transaksis', 'transaksis.id_driver', '=', 'drivers.id_driver')
            ->groupBy('drivers.id_driver')
            ->get();

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

    public function getreratadriver()
    {
        $drivers = DB::table('drivers')
            ->leftJoin('transaksis', 'transaksis.id_driver', '=', 'drivers.id_driver')
            ->selectRaw("ROUND(AVG(transaksis.rating_driver), 2) as rata_rating")
            ->groupBy('transaksis.id_driver')
            ->where('status_ketersediaan_driver', 'Tersedia')
            ->get();

        // $drivers = DB::table('drivers')->select("AVG(transaksis.rating_driver) as rata_rating")
        //     ->join('transaksis', 'transaksis.id_driver', '=', 'drivers.id_driver')
        //     // ->groupBy('transaksis.id_driver')
        //     ->get();

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

    public function showByStatusKeter()
    {
        $drivers = Driver::where('status_ketersediaan_driver', 'Tersedia')->get();

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

    public function showByAktif()
    {
        $drivers = Driver::where('isAktif', 1)->get();

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
        $validate = Validator::make(
            $storeData,
            [
                'nama_driver' => 'required|regex:/^[\pL\s\-]+$/u',
                'alamat_driver' => 'required',
                'email_driver' => 'required|email:rfc,dns|unique:Drivers',
                'status_ketersediaan_driver' => 'required|regex:/^[\pL\s\-]+$/u',
                // 'status_berkas' => 'required|regex:/^[\pL\s\-]+$/u',
                'isEnglish' => 'required',
                'tanggal_lahir_driver' => 'required',
                'jenis_kelamin' => 'required|regex:/^[\pL\s\-]+$/u',
                'no_telp_driver' => 'required|numeric|starts_with:08',
                'url_foto_driver' => 'required|max:1024|mimes:jpg,png,jpeg|image',
                'password',
                'tarif_sewa_driver' => 'required|numeric',
                'berkas_bebas_napza' => 'required|max:1024|mimes:jpg,png,jpeg|image',
                'berkas_sim' => 'required|max:1024|mimes:jpg,png,jpeg|image',
                'berkas_sehat_jiwa' => 'required|max:1024|mimes:jpg,png,jpeg|image',
                'berkas_sehat_jasmani' => 'required|max:1024|mimes:jpg,png,jpeg|image',
                'berkas_skck' => 'required|max:1024|mimes:jpg,png,jpeg|image',
                // 'rerata_rating_driver' => 'required|numeric',
                'isAktif' => 'required',
            ],
            [],
            [
                'nama_driver' => 'Nama Driver',
                'alamat_driver' => 'Alamat Driver',
                'email_driver' => 'Email Driver',
                'status_ketersediaan_driver' => 'Status Ketersediaan Driver',
                'isEnglish' => 'Bahasa Inggris Driver',
                'tanggal_lahir_driver' => 'Tanggal Lahir Driver',
                'jenis_kelamin' => 'Jenis Kelamin',
                'no_telp_driver' => 'Nomor Telepon',
                'url_foto_driver' => 'Foto Driver',
                'tarif_sewa_driver' => 'Tarif Sewa Driver',
                'berkas_bebas_napza' => 'Berkas Bebas Napza',
                'berkas_sim' => 'Berkas Sim',
                'berkas_sehat_jiwa' => 'Berkas Sehat Jiwa',
                'berkas_sehat_jasmani' => 'Berkas Sehat Jasmani',
                'berkas_skck' => 'Berkas SKCK',
                'isAktif' => 'Status Aktif',
            ]
        ); //Membuat rule validasi input

        $err_message = array(array('Pastikan Field Terisi Semuanya'));

        if (
            $request->nama_driver === 'null' || $request->alamat_driver === 'null' || $request->email_driver === 'null' || $request->status_ketersediaan_driver === 'null' ||
            $request->isEnglish === 'null' || $request->tanggal_lahir_driver === 'null' || $request->jenis_kelamin === 'null' ||
            $request->no_telp_driver === 'null' || $request->tarif_sewa_driver === 'null' || $request->berkas_bebas_napza === 'null' ||
            $request->berkas_sim === 'null' || $request->berkas_sehat_jiwa === 'null' || $request->berkas_sehat_jasmani === 'null' ||
            $request->berkas_skck === 'null' || $request->isAktif === 'null' || $request->url_foto_driver === 'null'
        ) {
            return response(['message' => $err_message], 400);
        }

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $fotoDriver = $request->url_foto_driver->store('foto_driver', ['disk' => 'public']);
        $berkasNapza = $request->berkas_bebas_napza->store('berkas_napza', ['disk' => 'public']);
        $berkasSim = $request->berkas_sim->store('berkas_sim', ['disk' => 'public']);
        $berkasJiwa = $request->berkas_sehat_jiwa->store('berkas_jiwa', ['disk' => 'public']);
        $berkasJasmani = $request->berkas_sehat_jasmani->store('berkas_jasmani', ['disk' => 'public']);
        $berkasSkck = $request->berkas_skck->store('berkas_skck', ['disk' => 'public']);

        $count = DB::table('drivers')->count() + 1;
        $id_generate = sprintf("%03d", $count);
        $datenow = Carbon::now()->format('dmy');
        $Driver = Driver::create([
            'id_driver' => 'DRV-' . $datenow . $id_generate,
            'nama_driver' => $request->nama_driver,
            'alamat_driver' => $request->alamat_driver,
            'email_driver' => $request->email_driver,
            'status_ketersediaan_driver' => $request->status_ketersediaan_driver,
            'status_berkas' => 'Not Verified',
            'isEnglish' => $request->isEnglish,
            'tanggal_lahir_driver' => $request->tanggal_lahir_driver,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telp_driver' => $request->no_telp_driver,
            'url_foto_driver' => $fotoDriver,
            'password' => bcrypt($request->tanggal_lahir_driver),
            'tarif_sewa_driver' => $request->tarif_sewa_driver,
            'berkas_bebas_napza' => $berkasNapza,
            'berkas_sim' => $berkasSim,
            'berkas_sehat_jiwa' => $berkasJiwa,
            'berkas_sehat_jasmani' => $berkasJasmani,
            'berkas_skck' => $berkasSkck,
            'rerata_rating_driver' => 0,
            'isAktif' => $request->isAktif,
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
        $validate = Validator::make(
            $updateData,
            [
                'nama_driver' => 'required|regex:/^[\pL\s\-]+$/u',
                'alamat_driver' => 'required',
                'email_driver' => ['required', 'email:rfc,dns', Rule::unique('drivers')->ignore($driver)],
                'status_ketersediaan_driver' => 'required|regex:/^[\pL\s\-]+$/u',
                // 'status_berkas' => 'required|regex:/^[\pL\s\-]+$/u',
                'isEnglish' => 'required',
                'tanggal_lahir_driver' => 'required',
                'jenis_kelamin' => 'required|regex:/^[\pL\s\-]+$/u',
                'no_telp_driver' => 'required|numeric|starts_with:08',
                'url_foto_driver' => 'max:1024|mimes:jpg,png,jpeg|image',
                'password',
                'tarif_sewa_driver' => 'required|numeric',
                'berkas_bebas_napza' => 'max:1024|mimes:jpg,png,jpeg|image',
                'berkas_sim' => 'max:1024|mimes:jpg,png,jpeg|image',
                'berkas_sehat_jiwa' => 'max:1024|mimes:jpg,png,jpeg|image',
                'berkas_sehat_jasmani' => 'max:1024|mimes:jpg,png,jpeg|image',
                'berkas_skck' => 'max:1024|mimes:jpg,png,jpeg|image',
                // 'rerata_rating_driver' => 'required|numeric',
                'isAktif' => 'required',
            ],
            [],
            [
                'nama_driver' => 'Nama Driver',
                'alamat_driver' => 'Alamat Driver',
                'email_driver' => 'Email Driver',
                'status_ketersediaan_driver' => 'Status Ketersediaan Driver',
                'isEnglish' => 'Bahasa Inggris Driver',
                'tanggal_lahir_driver' => 'Tanggal Lahir Driver',
                'jenis_kelamin' => 'Jenis Kelamin',
                'no_telp_driver' => 'Nomor Telepon',
                'url_foto_driver' => 'Foto Driver',
                'tarif_sewa_driver' => 'Tarif Sewa Driver',
                'berkas_bebas_napza' => 'Berkas Bebas Napza',
                'berkas_sim' => 'Berkas Sim',
                'berkas_sehat_jiwa' => 'Berkas Sehat Jiwa',
                'berkas_sehat_jasmani' => 'Berkas Sehat Jasmani',
                'berkas_skck' => 'Berkas SKCK',
                'isAktif' => 'Status Aktif',
            ]
        );

        $err_message = array(array('Pastikan Field Terisi Semuanya'));

        if (
            $request->nama_driver === 'null' || $request->alamat_driver === 'null' || $request->email_driver === 'null' || $request->status_ketersediaan_driver === 'null' ||
            $request->isEnglish === 'null' || $request->tanggal_lahir_driver === 'null' || $request->jenis_kelamin === 'null' ||
            $request->no_telp_driver === 'null' || $request->tarif_sewa_driver === 'null' || $request->isAktif === 'null'
        ) {
            return response(['message' => $err_message], 400);
        }

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $driver->nama_driver = $updateData['nama_driver'];
        $driver->alamat_driver = $updateData['alamat_driver'];
        $driver->email_driver = $updateData['email_driver'];
        $driver->status_ketersediaan_driver = $updateData['status_ketersediaan_driver'];
        // $driver->status_berkas = $updateData['status_berkas'];
        $driver->isEnglish = $updateData['isEnglish'];
        $driver->tanggal_lahir_driver = $updateData['tanggal_lahir_driver'];
        $driver->jenis_kelamin = $updateData['jenis_kelamin'];
        $driver->no_telp_driver = $updateData['no_telp_driver'];
        $driver->isAktif = $updateData['isAktif'];
        if (isset($request->url_foto_driver)) {
            $fotoDriver = $request->url_foto_driver->store('foto_driver', ['disk' => 'public']);
            $driver->url_foto_driver = $fotoDriver;
        }
        // $driver->url_foto_driver = $updateData['url_foto_driver'];
        $driver->password = $updateData['password'];
        $driver->tarif_sewa_driver = $updateData['tarif_sewa_driver'];
        if (isset($request->berkas_bebas_napza)) {
            $berkasNapza = $request->berkas_bebas_napza->store('berkas_napza', ['disk' => 'public']);
            $driver->berkas_bebas_napza = $berkasNapza;
        }
        // $driver->berkas_bebas_napza = $updateData['berkas_bebas_napza'];
        if (isset($request->berkas_sim)) {
            $berkasSim = $request->berkas_sim->store('berkas_sim', ['disk' => 'public']);
            $driver->berkas_sim = $berkasSim;
        }
        // $driver->berkas_sim = $updateData['berkas_sim'];
        if (isset($request->berkas_sehat_jiwa)) {
            $berkasJiwa = $request->berkas_sehat_jiwa->store('berkas_jiwa', ['disk' => 'public']);
            $driver->berkas_sehat_jiwa = $berkasJiwa;
        }
        // $driver->berkas_sehat_jiwa = $updateData['berkas_sehat_jiwa'];
        if (isset($request->berkas_sehat_jasmani)) {
            $berkasJasmani = $request->berkas_sehat_jasmani->store('berkas_jasmani', ['disk' => 'public']);
            $driver->berkas_sehat_jasmani = $berkasJasmani;
        }
        // $driver->berkas_sehat_jasmani = $updateData['berkas_sehat_jasmani'];
        if (isset($request->berkas_skck)) {
            $berkasSkck = $request->berkas_skck->store('berkas_skck', ['disk' => 'public']);
            $driver->berkas_skck = $berkasSkck;
        }
        // $driver->berkas_skck = $updateData['berkas_skck'];
        // $driver->rerata_rating_driver = $updateData['rerata_rating_driver'];


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

    public function updateBerkas(Request $request, $id)
    {
        $driver = Driver::find($id);

        if (is_null($driver)) {
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        if (isset($request->status_berkas)) {
            $driver->status_berkas = $updateData['status_berkas'];
        }


        if ($driver->save()) {
            return response([
                'message' => 'Berhasil Verifikasi Berkas Driver',
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