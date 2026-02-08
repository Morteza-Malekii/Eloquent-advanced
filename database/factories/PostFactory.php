<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>fake()->unique()->sentence(4),
            'body'=>fake()->text(256),
            'user_id'=> User::inRandomOrder()->value('id'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            Comment::factory()
            ->count(rand(2, 5))
            ->for($post, 'commentable')
            ->create();
        });
    }

    
}
