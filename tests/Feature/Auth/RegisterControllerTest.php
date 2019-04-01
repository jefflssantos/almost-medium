<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    protected $endpoint = '/api/auth/register';

    protected $credentials = [
        'email' => 'email@jeffsantos.com.br',
        'password' => '@nyp4ssw0rd'
    ];

    /** @test */
    public function can_register_a_user()
    {
        $this->json('POST', $this->endpoint, $this->credentials)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'email',
                    'api_token',
                    'updated_at',
                    'created_at'
                ]
            ])->assertJson([
                'data' => [
                    'email' => $this->credentials['email']
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $this->credentials['email']
        ]);
    }

    /** @test */
    public function can_not_register_a_user_with_a_weak_password()
    {
        $this->credentials['password'] = 'abc123';

        $this->json('POST', $this->endpoint, $this->credentials)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function can_not_register_a_user_with_invalid_email()
    {
        $this->credentials['email'] = 'emailjeffsantos.com.br';

        $this->json('POST', $this->endpoint, $this->credentials)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function can_not_register_a_user_with_missing_credentials()
    {
        $this->json('POST', $this->endpoint)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /** @test */
    public function can_not_register_a_user_with_existing_email()
    {
        factory(User::class)->create(['email' => $this->credentials['email']]);

        $this->json('POST', $this->endpoint, $this->credentials)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
