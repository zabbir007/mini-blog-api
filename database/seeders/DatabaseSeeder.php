<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Article;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $categories = Category::factory()->count(3)->create();

        Article::factory()->count(5)->create([
            'user_id' => $user->id,
            'category_id' => $categories->random()->id,
            'status' => 'published'
        ]);
    }
}
