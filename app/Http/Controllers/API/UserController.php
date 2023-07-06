<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt_auth')->except('index');
    }
    public function index()
    {
        try {
            $user = auth()->user()->name;
            return response()->json([
                'status' => true,
                'message' => 'success',
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
