<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Call;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!User::count()) {
            User::factory(10)->create();
        }
        
        if (!Post::count()) {
            Post::factory(10)->create();
        }

        if (!Comment::count()) {
            Comment::factory(100)->create();
        }

        if (Call::count()) {
            $comment = Call::select('calltime')
                ->orderBy('id', 'desc')
                ->first();
            $calltime = $comment ? strtotime($comment->calltime) : time();

            for ($i = 0; $i < 10000; $i++) {
                $calltime += rand(4, 30) * 60 + rand(1, 60);
                Call::factory()->create([
                    'user_id' => User::select('id')
                        ->orderByRaw('random()')
                        ->first()
                        ->id,
                    'calltime' => date('Y-m-d H:i:s', $calltime),
                    'duration_sec' => rand(30, 120)
                ]);
            }
        }
    }
}
