<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        // 1.新しい順に取得できない
        // $posts = Post::all();

        // 2.記述が長くなる
        // $posts = Post::orderByDesc('created_at')->get();

        // 3.latestメソッドがおすすめ
        $posts = Post::latest()->paginate(5);
        \Debugbar::info($posts);

        return view('posts.index', ['posts' => $posts]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = $request->user()->id;
        $post->save();
        return redirect('posts/'.$post->id);
    }

    public function show(Post $post)
    {
        return view('posts.show',['post' => $post]);
    }

    public function edit(Post $post)
    {
        $this->authorize('edit', $post);
        return view('posts.edit',['post' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('edit', $post);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
        return redirect('posts/'.$post->id);
    }

    public function destroy(Post $post)
    {
        $this->authorize('edit', $post);
        $post->delete();
        return redirect('posts');
    }

    // 各アクションの前に実行されるミドルウェア
    public function __construct()
    {
        // $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('verified')->except(['index', 'show']);
    }
}
