<?php

namespace Database\Seeders;

use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Like;
use App\Models\Post;
use App\Models\Seo;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'TuanTQ',
            'email' => 'admin@gamil.com',
            'password' => Hash::make('12341234'),
        ]);

        User::factory(9)->create(); // tổng cộng có 10 user
        $users = User::pluck('id'); // lấy danh sách ID

        Category::factory(5)->create();
        Tag::factory(10)->create();
        Post::factory(20)->create()->each(function ($post) use ($users) {
            $post->user_id = $users->random();
            $post->category_id = Category::inRandomOrder()->first()->id;
            $post->save();
            // Gắn tag ngẫu nhiên
            $post->tags()->attach(
                Tag::inRandomOrder()->take(rand(1, 3))->pluck('id')
            );

            // Tạo ảnh cho post
            Image::factory(rand(1, 2))->create([
                'imageable_type' => Post::class,
                'imageable_id' => $post->id,
            ]);

            // Tạo like ngẫu nhiên từ user
            Like::factory(rand(1, 5))->create([
                'likeable_type' => Post::class,
                'likeable_id' => $post->id,
                'user_id' => User::inRandomOrder()->first()->id,
            ]);

            // Tạo 1-5 comment cho Post
            Comment::factory(rand(1, 5))->create([
                'commentable_type' => Post::class,
                'commentable_id' => $post->id,
            ]);

            // Tạo SeoMeta cho Post
            Seo::factory()->create([
                'metaable_type' => Post::class,
                'metaable_id' => $post->id,
            ]);
        });
    }
}
