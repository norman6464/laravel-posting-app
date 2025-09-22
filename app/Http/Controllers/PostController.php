<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // 一覧ページ
    public function index() {
        // Authファサードを使ってログイン済みのユーザーの場合のみこのビューは表示できるじゃなきゃ
        // Laravel側が自動的にリダイレクトをしてログインページに移る
        // userモデルに設定したリレーションシップの影響でpostsメソッドが使える
        $posts = Auth::user()->posts()->orderBy('created_at', 'desc')->get();
        
        return view('posts.index', compact('posts'));
    }
    
    // 詳細ページ
    public function show(Post $post) {
        /* 実装について
        いままではroute/web.phpでidを受け取ってその値をPost::find($id)としていたが、
        route('posts.show', $post)とすることで直節リクエスト側がインスタンスをわたしている状態なので
        引数にPostインスタンスを定義する
        */
        return view('posts.show', compact('post'));
    }
    
}
