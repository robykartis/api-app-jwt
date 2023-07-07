<?php

namespace Database\Seeders;

use App\Models\CatPosts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CatPosts::create([
            'name' => 'Category 1'
        ]);
        CatPosts::create([
            'name' => 'Category 2'
        ]);
        CatPosts::create([
            'name' => 'Category 3'
        ]);
    }
}
