<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        try {
            $data = Post::all();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'user' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status_kode' => 500,
                'status' => false,
                'message' => $e->errorInfo
            ], 500);
        }
    }
}
