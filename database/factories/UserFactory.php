<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\Post;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'username' => $faker->unique()->userName,
        'active' => $faker->randomElement([0,1]),
        'group' => $faker->randomElement([1,2]), // 1:normall user, 2:super user
        'image' => 'image/default.jpg',
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Post::class,function(Faker $faker){
	return [
		'body' => $faker->paragraph(3),
		'user_id' => User::all()->random()->id,
		'image' => $faker->randomElement(['image/img1.jpg','image/img2.jpg','image/img3.jpg','image/img4.jpg','image/img5.jpg','image/img6.jpg','image/img7.jpg','image/img8.jpg','image/img9.jpg','image/img10.jpg']),
	];
});

$factory->define(Comment::class,function(Faker $faker){
	return [
		'comment' => $faker->paragraph(1),
		'user_id' => User::all()->random()->id,
		'post_id' => Post::all()->random()->id,
	];
});
