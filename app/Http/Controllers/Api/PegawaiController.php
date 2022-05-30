<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $pegawais = DB::table('pegawais')
            ->join('roles', 'roles.id_role', '=', 'pegawais.id_role')->get();

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

    public function showByStatus()
    {
        $pegawais = DB::table('pegawais')
            ->join('roles', 'roles.id_role', '=', 'pegawais.id_role')->where('isAktif', 1)->get();

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
        $validate = Validator::make(
            $storeData,
            [
                'nama_pegawai' => 'required|regex:/^[\pL\s\-]+$/u',
                'alamat_pegawai' => 'required',
                'email_pegawai' => 'required|email:rfc,dns|unique:Pegawais',
                'tanggal_lahir_pegawai' => 'required',
                'jenis_kelamin_pegawai' => 'required',
                'no_telp_pegawai' => 'required|numeric|starts_with:08',
                'url_foto_pegawai' => 'required|max:1024|mimes:jpg,png,jpeg|image',
                'isAktif' => 'required',
            ],
            [],
            [
                'nama_pegawai' => 'Nama Pegawai',
                'alamat_pegawai' => 'Alamat Pegawai',
                'email_pegawai' => 'Email Pegawai',
                'tanggal_lahir_pegawai' => 'Tanggal Lahir',
                'jenis_kelamin_pegawai' => 'Jenis Kelamin',
                'no_telp_pegawai' => 'Nomor Telepon',
                'url_foto_pegawai' => 'Foto Profil',
            ]
        ); // membuat rule validasi input

        $err_message = array(array('Pastikan Field Terisi Semuanya'));

        if (
            $request->id_role === 'null' || $request->nama_pegawai === 'null' || $request->alamat_pegawai === 'null' || $request->email_pegawai === 'null' ||
            $request->tanggal_lahir_pegawai === 'null' || $request->jenis_kelamin_pegawai === 'null' ||
            $request->no_telp_pegawai === 'null' || $request->url_foto_pegawai === 'null' || $request->isAktif === 'null'
        ) {
            return response(['message' => $err_message], 400);
        }

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $fotoPegawai = $request->url_foto_pegawai->store('foto_pegawai', ['disk' => 'public']);

        $pegawai = Pegawai::create([
            'id_role' => $request->id_role,
            'nama_pegawai' => $request->nama_pegawai,
            'alamat_pegawai' => $request->alamat_pegawai,
            'email_pegawai' => $request->email_pegawai,
            'tanggal_lahir_pegawai' => $request->tanggal_lahir_pegawai,
            'jenis_kelamin_pegawai' => $request->jenis_kelamin_pegawai,
            'no_telp_pegawai' => $request->no_telp_pegawai,
            'password' => Hash::make($request->tanggal_lahir_pegawai),
            'url_foto_pegawai' => $fotoPegawai,
            'isAktif' => $request->isAktif,
        ]);
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
        $validate = Validator::make(
            $updateData,
            [
                'nama_pegawai' => 'required|regex:/^[\pL\s\-]+$/u',
                'alamat_pegawai' => 'required',
                'email_pegawai' => ['max:60', 'required', 'email:rfc,dns', Rule::unique('pegawais')->ignore($pegawai)],
                'tanggal_lahir_pegawai' => 'required',
                'jenis_kelamin_pegawai' => 'required',
                'no_telp_pegawai' => 'required|numeric',
                // 'password',
                'url_foto_pegawai' => 'max:1024|mimes:jpg,png,jpeg|image',
                'isAktif' => 'required',
            ],
            [],
            [
                'nama_pegawai' => 'Nama Pegawai',
                'alamat_pegawai' => 'Alamat Pegawai',
                'email_pegawai' => 'Email Pegawai',
                'tanggal_lahir_pegawai' => 'Tanggal Lahir',
                'jenis_kelamin_pegawai' => 'Jenis Kelamin',
                'no_telp_pegawai' => 'Nomor Telepon',
                'url_foto_pegawai' => 'Foto Profil',
            ]
        );

        $err_message = array(array('Pastikan Field Terisi Semuanya'));

        if (
            $request->id_role === 'null' || $request->nama_pegawai === 'null' || $request->alamat_pegawai === 'null' || $request->email_pegawai === 'null' ||
            $request->tanggal_lahir_pegawai === 'null' || $request->jenis_kelamin_pegawai === 'null' ||
            $request->no_telp_pegawai === 'null' || $request->isAktif === 'null'
        ) {
            return response(['message' => $err_message], 400);
        }

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $pegawai->id_role = $updateData['id_role'];
        $pegawai->nama_pegawai = $updateData['nama_pegawai'];
        $pegawai->alamat_pegawai = $updateData['alamat_pegawai'];
        $pegawai->email_pegawai = $updateData['email_pegawai'];
        $pegawai->tanggal_lahir_pegawai = $updateData['tanggal_lahir_pegawai'];
        $pegawai->jenis_kelamin_pegawai = $updateData['jenis_kelamin_pegawai'];
        $pegawai->no_telp_pegawai = $updateData['no_telp_pegawai'];
        // $pegawai->password = $updateData['password'];
        if (isset($request->url_foto_pegawai)) {
            $fotoPegawai = $request->url_foto_pegawai->store('foto_pegawai', ['disk' => 'public']);
            $pegawai->url_foto_pegawai = $fotoPegawai;
        }
        $pegawai->isAktif = $updateData['isAktif'];

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