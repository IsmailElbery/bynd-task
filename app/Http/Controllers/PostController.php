<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //

    public function index()
    {
        $data = Post::all();
        return response()->json([
            'success' => true,
            'message' => 'Post List',
            'data' => $data
        ], 200);
    }

    public function show($id)
    {
        $post = Post::find($id);
        if ($post) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Post',
                'data' => $post
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post Not Found',
                'data' => ''
            ], 404);
        }
    }

    public function store(PostRequest $request)
    {
        $data = $request->all();
        $auth = auth()->user();
        if ($auth) {
            $data['author_id'] = $auth->id;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found',
                'data' => ''
            ], 404);
        }
        $post = Post::create($data);
        if ($post) {
            return response()->json([
                'success' => true,
                'message' => 'Post Created',
                'data' => $post
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post Failed to Save',
                'data' => ''
            ], 400);
        }
    }

    public function update(PostRequest $request, $id)
    {
        $data = $request->all();
        $auth = auth()->user();
        $post = Post::find($id);
        $data['author_id'] = $auth->id;
        $update = $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'private' => $data['private'] ?? $post->private,
            'author_id' => $data['author_id']
        ]);
        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Post Updated',
                'data' => $post
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post Failed to Update',
                'data' => ''
            ], 500);
        }
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post) {
            $delete = $post->delete();
            if ($delete) {
                return response()->json([
                    'success' => true,
                    'message' => 'Post Deleted',
                    'data' => $post
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Post Failed to Delete',
                    'data' => ''
                ], 500);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post Not Found',
                'data' => ''
            ], 404);
        }
    }

    public function getPrivatePosts()
    {
        $auth = auth()->user();
        $posts = Post::where('private', 1)->where('author_id', $auth->id)->get();
        return response()->json([
            'success' => true,
            'message' => 'Private Post List',
            'data' => $posts
        ], 200);
    }

    public function getPublicPosts()
    {
        $posts = Post::where('private', 0)->get();
        return response()->json([
            'success' => true,
            'message' => 'Public Post List',
            'data' => $posts
        ], 200);
    }
}
