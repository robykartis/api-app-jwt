<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3',
            'password_confirmation' => 'required|same:password',
        ], [
            'name.required' => 'Kolom nama tidak boleh kosong...!',
            'email.required' => 'Kolom email tidak boleh kosong...!',
            'email.unique' => 'Email yang anda masukan sudah terdaftar...!',
            'password.required' => 'Kolom password tidak boleh kosong...!',
            'password.min' => 'Password yang dimasukan minimal 3 karakter huruf dan angka...!',
            'password_confirmation.required' => 'Kolom konfirmasi password tidak boleh kosong...!',
            'password_confirmation.same' => 'Konfirmasi password yang anda masukan tidak sama ualngi kembali...!',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_kode' => 201,
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Daftar akun berhasil',
                'data' => $user
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errorInfo
            ], 500);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:3',
        ], [
            'email.required' => 'Kolom email tidak boleh kosong...!',
            'password.required' => 'Kolom password tidak boleh kosong...!',
            'password.min' => 'Password yang dimasukan minimal 3 karakter huruf dan angka...!',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 200);
        }
        try {
            $credentials = $request->only('email', 'password');
            $token = Auth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }
            $user = Auth::user();
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status_kode' => 500,
                'status' => false,
                'message' => $e->errorInfo
            ], 500);
        }
    }
<<<<<<< HEAD
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $value)->first();

                    if (!$user) {
                        $fail('Email yang Anda masukkan belum terdaftar.');
                    }
                },
            ],
            'password' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $user = User::where('email', $request->email)->first();

                    if ($user && !Hash::check($value, $user->password)) {
                        $fail('Password yang Anda masukkan salah.');
                    }
                },
            ],
        ], [
            'email.required' => 'Kolom email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kolom password tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
=======
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
>>>>>>> 314949991617a91faba269d742c9e09804430f31
        ]);
    }
}
