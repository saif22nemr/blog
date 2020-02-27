<?php

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // for disable foreign key for some time

        User::truncate();
        Post::truncate();
        Comment::truncate();

        User::flushEventListeners();
        Post::flushEventListeners();
        Comment::flushEventListeners();

        factory(User::class,50)->create();
        factory(Post::class,200)->create();
        factory(Comment::class,1100)->create();
    }
}
