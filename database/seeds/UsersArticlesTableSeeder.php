<?php

use Illuminate\Database\Seeder;

class UsersArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 3)
            ->create();
            // ->each(function ($user) {
            //         $user->posts()->save(factory(App\Post::class)->make());
            //     });
    }
}
