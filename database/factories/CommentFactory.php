<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $post = Post::select('id', 'user_id')
            ->orderByRaw('random()')
            ->first();

        return [
            'user_id' => $post->user_id,
            'post_id' => $post->id,
            'content' => fake()->paragraph()
        ];
    }
}
