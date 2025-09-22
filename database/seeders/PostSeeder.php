<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PostFactoryクラスで定義した内容をもとづいてダミーデータを5つ生成し、postsテーブルに追加する
        Post::factory()->count(5)->create();
    }
}
