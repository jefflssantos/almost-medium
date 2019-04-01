<?php

use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(App\User::class)->create([
                'email' => 'email@jeffsantos.com.br',
                'api_token' => 'qUYyWWHKtwzYPNsZOVbwcKDEqtHAfKrdJVdVSDnk7tnFeNyMKInqOSW1GBI3',
            ]);

        factory(App\Article::class, 16)->create([
            'user_id' => $user->id
        ]);
    }
}
