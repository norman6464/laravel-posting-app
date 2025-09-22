<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
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
    
    public function create() {
        return view('posts.create');
    }
    
    // 作成機能
    public function store(PostRequest $request) { // 引数はフォームリクエストで自動でバリデーションをさせる
        // 最初は必ずインスタンス化
        $post = new Post();
        $post->title = $request->input('title'); // リクエストパラメータを取得する（name属性の値title）
        $post->content = $request->input('content');
        $post->user_id = Auth::id(); // 現在ログインしているユーザーのID
        $post->save(); // 最後は必ず保存
        
        return redirect()->route('posts.index')->with('flash_message', '投稿が完了しました。'); // withメソッドでflashメッセージを生成する
    }
    
    // 編集ページで引数にモデルインスタンスをわたす、
    public function edit(Post $post) {
        // postsテーブルの外部キー（user_id）が今ログインしているユーザーと一致しなかったら
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('errorMessage', '不正なアクセスです。');
        }
        
        return view('posts.edit', compact('post'));
    }
    
    // 更新機能
    public function update(PostRequest $request, Post $post) {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('errorMessage', '不正なアクセスです。');
        }
        
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();
        
        return redirect()->route('posts.show', $post)->with('flash_message', '投稿を編集しました。');
    }
    
}
