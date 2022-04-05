<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;

class RoleController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $roles = Role::all();

        if (count($roles) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $roles
            ], 200); // return data semua role dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data role kosong
    }

    public function show($id_role)
    {
        $role = Role::find($id_role);

        if (!is_null($role)) {
            return response([
                'message' => 'Retrieve Role Success',
                'data' => $role
            ], 200);
        } // return data role yang ditemukan dalam bentuk json

        return response([
            'message' => 'Role Not Found',
            'data' => null
        ], 404); // return message saat data role tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'nama_role' => 'required|unique:roles|regex:/^[\pL\s\-]+$/u',
        ]); //Membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }
        $Role = Role::create($storeData);
        return response([
            'message' => 'Add role Success',
            'data' => $Role
        ], 200); //Return message data role baru dalam bentuk JSON
    }

    public function destroy($id_role)
    {
        $role = Role::find($id_role);

        if (is_null($role)) {
            return response([
                'message' => 'Role Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($role->delete()) {
            return response([
                'message' => 'Delete Role Success',
                'data' => $role
            ], 200);
        } // return message saat berhasil menghapus data role

        return response([
            'message' => 'Delete Role Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data role
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (is_null($role)) {
            return response([
                'message' => 'Role Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_role' => ['required', Rule::unique('roles')->ignore($role)],
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $role->nama_role = $updateData['nama_role'];

        if ($role->save()) {
            return response([
                'message' => 'Update Role Success',
                'data' => $role
            ], 200);
        }

        return response([
            'message' => 'Update Role Failed',
            'data' => null,
        ], 400); // return message saat role gagal di edit
    }
}