<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

    public function index()
    {
        $users = User::paginate(5);
        return view('users.index',['users' => $users]);
    }

    // 追加用のフォーム画面へ移動
    public function create()
    {
        return view('users.create');
    }

    // 実際の追加処理
    // 終わったら、作ったばかりのユーザのページへ移動
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        return redirect('users/'.$user->id);
    }

    public function show(User $user)
    {
        // そのユーサが投稿した記事のうち、最新5件を取得する
        $user->posts = $user->posts()->paginate(5);
        return view('users.show', ['user' => $user]);
    }

    //更新用フォームへ移動
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    // 実際の更新処理
    // 終わったら、そのユーザのページへ移動
    public function update(Request $request, User $user)
    {
        $user->name = $request->name;
        $user->save();
        return redirect('users/'.$user->id);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect('users');
    }
}
