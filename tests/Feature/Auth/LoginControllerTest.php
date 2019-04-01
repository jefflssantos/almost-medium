<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class LoginControllerTest extends TestCase
{
    protected $endpoint = '/api/auth/login';

    protected $credentials = [
        'email' => 'email@jeffsantos.com.br',
        'password' => '@nyp4ssw0rd'
    ];

    /** @test */
    public function can_login_a_user()
    {
        $user = factory(User::class)->create([
            'email' => $this->credentials['email'],
            'password' => Hash::make($this->credentials['password'])
        ]);

        $this->json('POST', $this->endpoint, $this->credentials)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'email',
                    'api_token',
                    'updated_at',
                    'created_at'
                ]
            ])->assertJson([
                'data' => [
                    'email' => $user->email,
                    'api_token' => $user->api_token
                ]
            ]);
    }

    /** @test */
    public function can_not_login_a_user_with_missing_credentials()
    {
        $this->json('POST', $this->endpoint)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /** @test */
    public function can_not_login_a_user_with_wrong_password()
    {
        factory(User::class)->create([
            'email' => $this->credentials['email'],
            'password' => Hash::make($this->credentials['password'])
        ]);

        $this->credentials['password'] = 'wrongpassword';

        $this->json('POST', $this->endpoint, $this->credentials)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
