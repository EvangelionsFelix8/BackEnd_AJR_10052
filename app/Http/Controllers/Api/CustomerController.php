<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $customers = Customer::all();

        if (count($customers) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $customers
            ], 200); // return data semua promo dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data promo kosong
    }

    public function show($id_driver)
    {
        $customer = Customer::find($id_driver);

        if (!is_null($customer)) {
            return response([
                'message' => 'Retrieve Customer Success',
                'data' => $customer
            ], 200);
        } // return data detailjadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ], 404); // return message saat data detailjadwal tidka ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'nama_customer' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat_customer' => 'required',
            'tanggal_lahir_customer' => 'required',
            'jenis_kelamin' => 'required',
            'email_customer' => 'required|email:rfc,dns|unique:Customers',
            'password' => 'required',
            'no_telp_customer' => 'required|numeric|starts_with:08',
            'berkas_customer' => 'required',
            'status_berkas' => 'required',
            'no_tanda_pengenal' => 'required',
            'no_sim' => 'required',
        ]); //Membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }
        $count = DB::table('customers')->count() + 1;
        $id_generate = sprintf("%03d", $count);
        $datenow = Carbon::now()->format('ymd');
        $Customer = Customer::create([
            'id_customer' => 'CUS' . $datenow . '-' . $id_generate,
            'nama_customer' => $request->nama_customer,
            'alamat_customer' => $request->alamat_customer,
            'tanggal_lahir_customer' => $request->tanggal_lahir_customer,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email_customer' => $request->email_customer,
            'password' => $request->password,
            'no_telp_customer' => $request->no_telp_customer,
            'berkas_customer' => $request->berkas_customer,
            'status_berkas' => $request->status_berkas,
            'no_tanda_pengenal' => $request->no_tanda_pengenal,
            'no_sim' => $request->no_sim,
        ]);

        return response([
            'message' => 'Add Customer Success',
            'data' => $Customer
        ], 200); //Return message data Customer baru dalam bentuk JSON
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (is_null($customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_customer' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat_customer' => 'required',
            'tanggal_lahir_customer' => 'required',
            'jenis_kelamin' => 'required',
            'email_customer' => ['required', 'email:rfc,dns', Rule::unique('customers')->ignore($customer)],
            'password' => 'required',
            'no_telp_customer' => 'required|numeric|starts_with:08',
            'berkas_customer' => 'required',
            'status_berkas' => 'required',
            'no_tanda_pengenal' => 'required',
            'no_sim' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $customer->nama_customer = $updateData['nama_customer'];
        $customer->alamat_customer = $updateData['alamat_customer'];
        $customer->tanggal_lahir_customer = $updateData['tanggal_lahir_customer'];
        $customer->jenis_kelamin = $updateData['jenis_kelamin'];
        $customer->email_customer = $updateData['email_customer'];
        $customer->password = $updateData['password'];
        $customer->no_telp_customer = $updateData['no_telp_customer'];
        $customer->berkas_customer = $updateData['berkas_customer'];
        $customer->status_berkas = $updateData['status_berkas'];
        $customer->no_tanda_pengenal = $updateData['no_tanda_pengenal'];
        $customer->no_sim = $updateData['no_sim'];

        if ($customer->save()) {
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer
            ], 200);
        }

        return response([
            'message' => 'Update Customer Failed',
            'data' => null,
        ], 400); // return message saat detailjadwal gagal di edit
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (is_null($customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if ($customer->delete()) {
            return response([
                'message' => 'Delete Customer Success',
                'data' => $customer
            ], 200);
        } // return message saat berhasil menghapus data detailjadwal

        return response([
            'message' => 'Delete Customer Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data detailjadwal
    }
}