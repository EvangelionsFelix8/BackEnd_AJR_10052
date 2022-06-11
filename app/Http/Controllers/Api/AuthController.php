<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;
use App\Models\Driver;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make(
            $loginData,
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.email' => 'Kesalahan format input email',
            ]
        );

        if (is_null($request->email) || is_null($request->password)) {
            return response(['message' => 'Inputan tidak boleh kosong'], 400);
        }

        $customer = null;
        $driver = null;
        $pegawai = null;

        //get token with random string//
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        if (Customer::where('email_customer', '=', $loginData['email'])->first()) {
            $loginCustomer = Customer::where('email_customer', '=', $loginData['email'])->first();

            if ($loginCustomer['email_customer'] != $loginData['email']) {
                return response([
                    'message' => 'Email Anda salah',
                    'data' => $customer
                ], 400);
            }

            if (Hash::check($request['password'], $loginCustomer['password'])) {
                $customer = Customer::where('email_customer', $loginData['email'])->first();
            } else {
                return response([
                    'message' => 'Password Anda salah',
                    'data' => $customer
                ], 400);
            }
            $token = bcrypt($randomString);
            return response([
                'message' => 'Berhasil Login',
                'kode' => 1,
                'data' => $customer,
                'token' => $token
            ], 200);
        } else if (Driver::where('email_driver', '=', $loginData['email'])->first()) {
            $loginDriver = Driver::where('email_driver', '=', $loginData['email'])->first();

            if ($loginDriver['email_driver'] != $loginData['email']) {
                return response([
                    'message' => 'Email Anda salah',
                    'data' => $driver
                ], 400);
            }

            if (Hash::check($loginData['password'], $loginDriver['password'])) {
                $driver = Driver::where('email_driver', $loginData['email'])->first();
            } else {
                return response([
                    'message' => 'Password Anda salah',
                    'data' => $driver
                ], 400);
            }
            $token = bcrypt($randomString);
            return response([
                'message' => 'Berhasil Login',
                'kode' => 2,
                'data' => $driver,
                'token' => $token
            ]);
        } else if (Pegawai::where('email_pegawai', '=', $loginData['email'])->first()) {
            $loginPegawai = Pegawai::where('email_pegawai', '=', $loginData['email'])->first();

            error_log($loginPegawai['email_pegawai']);

            if ($loginPegawai['email_pegawai'] != $loginData['email']) {
                return response([
                    'message' => 'Email Anda salah',
                    'data' => $pegawai
                ], 400);
            }

            if (Hash::check($loginData['password'], $loginPegawai['password'])) {
                $pegawai = Pegawai::where('email_pegawai', $loginData['email'])->first();
            } else {
                return response([
                    'message' => 'Password Anda salah',
                    'data' => $pegawai
                ], 400);
            }

            $token = bcrypt($randomString);
            return response([
                'message' => 'Berhasil Login ',
                'kode' => 3,
                'data' => $pegawai,
                'token' => $token
            ]);
        } else {
            return response([
                'message' => 'Email Anda salah',
            ], 400);
        }

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response([
            'message' => 'Berhasil Logout',
        ]);
    }
}