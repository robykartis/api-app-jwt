<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt_auth')->except('');
    }
    public function index()
    {
        $user = User::all();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $user
        ], 200);
    }
    public function store(Request $request)
    {
        $this->authorize('create-edit-delete-users');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required',
            'password' => [
                'required',
                'min:3',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&_])[A-Za-z\d@$!%*#?&_]+$/',
            ],
            'password_confirmation' => 'required|same:password',
        ], [
            'name.required' => 'Kolom nama tidak boleh kosong.',
            'email.required' => 'Kolom email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email yang Anda masukkan sudah terdaftar.',
            'role_id.required' => 'Pilih role.',
            'password.required' => 'Kolom password tidak boleh kosong.',
            'password.min' => 'Password yang Anda masukkan minimal 3 karakter huruf dan angka.',
            'password.regex' => 'Password harus mengandung setidaknya satu huruf besar, satu huruf kecil, satu angka, dan satu simbol.',
            'password_confirmation.required' => 'Kolom konfirmasi password tidak boleh kosong.',
            'password_confirmation.same' => 'Konfirmasi password yang Anda masukkan tidak sama. Silakan ulangi kembali.',
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
            $user->role_id = $request->role_id;
            $user['created_by'] = auth()->user()->name;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Akun berhasil ditambahkan',
                'data' => $user
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->errorInfo
            ], 500);
        }
    }
    public function show($id)
    {
        try {
            $user = User::find($id);
            return response()->json([
                'status' => true,
                'message' => 'Show akun berhasil',
                'data' => $user
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->errorInfo
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $this->authorize('create-edit-delete-users');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required',
            'password' => [
                'nullable',
                'min:3',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&_])[A-Za-z\d@$!%*#?&_]+$/',
            ],
            'password_confirmation' => 'nullable|same:password',
        ], [
            'name.required' => 'Kolom nama tidak boleh kosong.',
            'email.required' => 'Kolom email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email yang Anda masukkan sudah terdaftar.',
            'role_id.required' => 'Pilih role.',
            'password.min' => 'Password yang Anda masukkan minimal 3 karakter huruf dan angka.',
            'password.regex' => 'Password harus mengandung setidaknya satu huruf besar, satu huruf kecil, satu angka, dan satu simbol.',
            'password_confirmation.same' => 'Konfirmasi password yang Anda masukkan tidak sama. Silakan ulangi kembali.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_kode' => 201,
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }
        try {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role_id = $request->role_id;
            $user['update_by'] = auth()->user()->name;
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Akun berhasil diperbarui',
                'data' => $user
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errorInfo
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'Akun berhasil dihapus.',
                'data' => $user
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->errorInfo
            ], 500);
        }
    }
}
