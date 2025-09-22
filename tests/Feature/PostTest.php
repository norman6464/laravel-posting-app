<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    // 未ログインのユーザーは投稿一覧ページにアクセスできない
    public function test_guest_cannot_access_posts_index()
    {
        $response = $this->get(route('posts.index'));

        $response->assertRedirect(route('login'));
    }

    // ログイン済みのユーザーは投稿一覧ページにアクセスできる
    public function test_user_can_access_posts_index()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    // 未ログインのユーザーは投稿詳細ページにアクセスできない
    public function test_guest_cannot_access_posts_show()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('posts.show', $post));

        $response->assertRedirect(route('login'));
    }

    // ログイン済みのユーザーは投稿詳細ページにアクセスできる
    public function test_user_can_access_posts_show()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }
    
    // 未ログインの場合はユーザーは新規投稿ページにアクセスできない
    public function test_guest_cannot_access_posts_create() {
        $response = $this->get(route('posts.create'));
        
        $response->assertRedirect(route('login'));
    }
    
    // ログイン済みのユーザーは新規投稿ページにアクセスできる
    public function test_user_can_access_posts_create() {
        $user = User::factory()->create();
        
        // acting（代理）でログインするユーザーを決めるメソッド
        $response = $this->actingAs($user)->get(route('posts.create'));
        
        $response->assertStatus(200);
    }
    
    // 未ログインのユーザーは投稿を作成できない
    public function test_guest_cannot_access_posts_store() {
        $post = [
            'title' => 'プログラミング学習1日目',
            'content' => '今日からプログラミング学習開始',
        ];
        
        $response = $this->post(route('posts.store', $post));
        // 未ログインなのでpostsテーブルに反映されていないか（ログインしないと作成できない）
        $this->assertDatabaseMissing('posts', $post);
        
        $response->assertRedirect(route('login'));
        
    }
    
    // ログイン済みのユーザーは投稿を作成できる
    public function test_user_can_access_posts_store() {
        $user = User::factory()->create();
        
        $post = [
            'title' => 'プログラミング学習1日目',
            'content' => '今日からプログラミング学習開始',
        ];
        
        $response = $this->actingAs($user)->post(route('posts.store', $post));
        
        // $postの値がpostsテーブルに存在するかを確かめる（ログインしているので作成できているはず）
        $this->assertDatabaseHas('posts', $post);
        
        $response->assertRedirect(route('posts.index'));
    }
    
}