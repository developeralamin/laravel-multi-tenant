<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Post;
use Illuminate\Http\Request;

class PostController
{
  public function index()
    {
       $posts = Post::all();
       return response()->json($posts);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        $post = Post::create($data);
        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        return response()->json($post);
    }

    public function update(Request $request, Post $post)
    {
        $post->update(
            $request->only('title', 'content')
        );

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }
}
