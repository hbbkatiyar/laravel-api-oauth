<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function index()
    {
        $comments = Comment::with('user', 'post')->get();

        return response()->json($comments);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|numeric',
                'body' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $payload = $request->all();
            $payload["user_id"] = $request->user()->id;
            $comment = Comment::create($payload);

            return response()->json($comment);
        } catch(\Exception $e) {
            return response()->json(new \Illuminate\Support\MessageBag(['errors'=>$e->getMessage()]), 404);
        }
    }

    public function show($id)
    {
        try {
            $comment = Comment::with('user', 'post')->findOrFail($id);

            return response()->json($comment);
        } catch(\Exception $e) {
            return response()->json(new \Illuminate\Support\MessageBag(['errors'=>$e->getMessage()]), 404);
        }
    }
}
