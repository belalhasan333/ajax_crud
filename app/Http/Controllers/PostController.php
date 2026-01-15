<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->paginate(5);
        return view('posts.index', compact('posts'));
    }

    /**
     * Store a newly created post in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price
        ]);

        return response()->json([
            'status' => true,
            'post' => $post,
            'message' => 'Post created successfully.'
        ]);
    }

    /**
     * Display the specified post
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found.'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'post' => $post
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price
        ]);

        return response()->json([
            'status' => true,
            'post' => $post,
            'message' => 'Post updated successfully.'
        ]);
    }

    /**
     * Remove the specified post from storage (AJAX).
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found.'
            ], 404);
        }

        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully.'
        ]);
    }
}
