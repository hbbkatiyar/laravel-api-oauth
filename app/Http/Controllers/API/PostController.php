<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'comments')->get();

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'body' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
        
            $payload = $request->all();
            $payload["user_id"] = $request->user()->id;
            $post = Post::create($payload);

            return response()->json($post);
        } catch(\Exception $e) {
            return response()->json(new \Illuminate\Support\MessageBag(['errors'=>$e->getMessage()]), 404);
        }
    }

    public function show($id)
    {
        try {
            $post = Post::with('user', 'comments')->findOrFail($id);

            return response()->json($post);
        } catch(\Exception $e) {
            return response()->json(new \Illuminate\Support\MessageBag(['errors'=>$e->getMessage()]), 404);
        }
    }
}
