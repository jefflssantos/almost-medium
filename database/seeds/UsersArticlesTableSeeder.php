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
        factory(App\User::class, 5)
            ->create()
            ->each(function ($user) {
                    factory(App\Article::class, 16)->create([
                        'user_id' => $user->id
                    ]);
                });
    }
}
