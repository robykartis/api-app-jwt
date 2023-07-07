<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Post::create([
            'title' => 'Posts 1',
            'description' => 'Description Post 1',
            'content' => 'Content Posts 1',
            'role_id' => 1,
            'cat_post_id' => 1
        ]);
    }
}
