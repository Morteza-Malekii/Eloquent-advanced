<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->chunkById(200, function ($users) {
            foreach ($users as $user) {
                $user->images()->save(
                    Image::factory()->avatar()->make()
                );
            }
        });

        // 2) برای هر post یک cover + چند regular
        Post::query()->chunkById(200, function ($posts) {
            foreach ($posts as $post) {
                $post->images()->save(
                    Image::factory()->cover()->make()
                );

                $post->images()->saveMany(
                    Image::factory()->count(3)->regular()->make()
                );
            }
        });
    }
}
