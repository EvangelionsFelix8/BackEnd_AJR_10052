<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function countTransaction($id)
    {
        $from = Carbon::now()->format('ymd');
        $customer = DB::table('customers')
            ->join('transaksis', 'transaksis.id_customer', '=', 'customers.id_customer')
            ->selectRaw("COUNT(transaksis.id_customer) as jumlah")
            ->groupBy('customers.id_customer')
            ->where('transaksis.id_customer', '=', $id)
            ->count();

        if (!is_null($customer)) {
            return response([
                'message' => 'Retrieve Transaksi Success',
                'data' => $customer
            ], 200);
        } // return data detailjadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ], 404); // return message saat data detailjadwal tidka ditemukan
    }

    public function countTransactionBatal($id)
    {
        $customer = DB::table('customers')
            ->join('transaksis', 'transaksis.id_customer', '=', 'customers.id_customer')
            ->selectRaw("COUNT(transaksis.id_customer) as jumlah")
            ->groupBy('customers.id_customer')
            ->where('transaksis.id_customer', '=', $id)
            ->where('status_transaksi', '=', 'Batal')
            ->count();

        if (!is_null($customer)) {
            return response([
                'message' => 'Retrieve Transaksi Success',
                'data' => $customer
            ], 200);
        } // return data detailjadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ], 404); // return message saat data detailjadwal tidka ditemukan
    }

    public function countTransactionDone($id)
    {
        $customer = DB::table('customers')
            ->join('transaksis', 'transaksis.id_customer', '=', 'customers.id_customer')
            ->selectRaw("COUNT(transaksis.id_customer) as jumlah")
            ->groupBy('customers.id_customer')
            ->where('transaksis.id_customer', '=', $id)
            ->where('status_transaksi', '=', 'Sudah Lunas (Selesai)')
            ->count();

        if (!is_null($customer)) {
            return response([
                'message' => 'Retrieve Transaksi Success',
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
            // 'password' => 'required',
            'no_telp_customer' => 'required|numeric|starts_with:08',
            // 'status_berkas' => 'required',
            'no_tanda_pengenal' => 'required',
            'no_sim' => 'max:1024|mimes:jpg,png,jpeg|image',
        ], [], [
            'nama_customer' => 'Nama Customer',
            'alamat_customer' => 'Alamat Customer',
            'tanggal_lahir_customer' => 'Tanggal Lahir Customer',
            'jenis_kelamin' => 'Jenis Kelamin',
            'email_customer' => 'Email Customer',
            'no_telp_customer' => 'Nomor Telepon',
            'no_tanda_pengenal' => 'Foto Tanda Pengenal',
            'no_sim' => 'Foto SIM',
        ]); //Membuat rule validasi input

        $err_message = array(array('Pastikan Field Terisi Semuanya (Kecuali SIM (jika tidak Punya))'));

        error_log($request->no_tanda_pengenal);
        if (
            $request->nama_customer === 'null' || $request->alamat_customer === 'null' || $request->tanggal_lahir_customer === 'null' ||
            $request->jenis_kelamin === 'null' || $request->email_customer === 'null' ||
            $request->no_telp_customer === 'null' || $request->no_tanda_pengenal == "undefined"

        ) {
            return response(['message' => $err_message], 400);
        }

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $fotoTandaPengenal = $request->no_tanda_pengenal->store('foto_tanda_pengenal', ['disk' => 'public']);
        if (isset($request->no_sim)) {
            $fotoSim = $request->no_sim->store('foto_sim', ['disk' => 'public']);
        } else {
            $fotoSim = NULL;
        }
        // $fotoSim = $request->no_sim->store('foto_sim', ['disk' => 'public']);
        // $fotoSim = $request->no_sim->store('foto_sim', ['disk' => 'public']);

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
            'password' => Hash::make($request->tanggal_lahir_customer),
            'no_telp_customer' => $request->no_telp_customer,
            'status_berkas' => 'Not Verified',
            'no_tanda_pengenal' => $fotoTandaPengenal,
            'no_sim' => $fotoSim,
        ]);

        return response([
            'message' => 'Berhasil melakukan Registrasi Akun',
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
            // 'password' => 'required',
            'no_telp_customer' => 'required|numeric|starts_with:08',
            'no_tanda_pengenal' => 'max:1024|mimes:jpg,png,jpeg|image',
            'no_sim' => 'max:1024|mimes:jpg,png,jpeg|image',
        ], [], [
            'nama_customer' => 'Nama Customer',
            'alamat_customer' => 'Alamat Customer',
            'tanggal_lahir_customer' => 'Tanggal Lahir Customer',
            'jenis_kelamin' => 'Jenis Kelamin',
            'email_customer' => 'Email Customer',
            'no_telp_customer' => 'Nomor Telepon',
            'no_tanda_pengenal' => 'Foto Tanda Pengenal',
            'no_sim' => 'Foto SIM',
        ]);

        $err_message = array(array('Pastikan Field Terisi Semuanya'));

        if (
            $request->nama_customer === 'null' || $request->alamat_customer === 'null' || $request->tanggal_lahir_customer === 'null' ||
            $request->jenis_kelamin === 'null' || $request->email_customer === 'null' ||
            $request->no_telp_customer === 'null' || $request->no_tanda_pengenal === 'null'
        ) {
            return response(['message' => $err_message], 400);
        }

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $customer->nama_customer = $updateData['nama_customer'];
        $customer->alamat_customer = $updateData['alamat_customer'];
        $customer->tanggal_lahir_customer = $updateData['tanggal_lahir_customer'];
        $customer->jenis_kelamin = $updateData['jenis_kelamin'];
        $customer->email_customer = $updateData['email_customer'];
        // $customer->password = $updateData['password'];
        $customer->no_telp_customer = $updateData['no_telp_customer'];
        // $customer->status_berkas = $updateData['status_berkas'];

        if (isset($request->no_tanda_pengenal)) {
            $fotoTandaPengenal = $request->no_tanda_pengenal->store('foto_tanda_pengenal', ['disk' => 'public']);
            $customer->no_tanda_pengenal = $fotoTandaPengenal;
        }
        // $customer->no_tanda_pengenal = $updateData['no_tanda_pengenal'];
        if (isset($request->no_sim)) {
            $fotoSim = $request->no_sim->store('foto_sim', ['disk' => 'public']);
            $customer->no_sim = $fotoSim;
        }
        // $customer->no_sim = $updateData['no_sim'];

        if ($customer->save()) {
            return response([
                'message' => 'Update Profile Anda Success',
                'data' => $customer
            ], 200);
        }

        return response([
            'message' => 'Update Profile Anda Failed',
            'data' => null,
        ], 400); // return message saat detailjadwal gagal di edit
    }

    public function updateBerkas(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (is_null($customer)) {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        // if (isset($request->status_berkas)) {
        $customer->status_berkas = $updateData['status_berkas'];
        // }


        if ($customer->save()) {
            return response([
                'message' => 'Berhasil Verifikasi Berkas Customer',
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