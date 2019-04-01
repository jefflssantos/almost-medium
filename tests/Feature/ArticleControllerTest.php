<?php

namespace Tests\Feature;

use App\User;
use App\Article;
use Tests\TestCase;
use App\Http\Resources\Article as ArticleResource;

class ArticleControllerTest extends TestCase
{
    /** @test */
    public function can_list_articles()
    {
        $user = factory(User::class)->create();

        $articles = factory(Article::class, 10)->create([
            'user_id' => $user->id
        ]);

        $articles->load('user');

        $this->actingAs($user, 'api')
            ->get('/api/articles')
            ->assertStatus(200)
            ->assertJson([
                'data' => ArticleResource::collection($articles)->toArray([])
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'body',
                        'title',
                        'slug',
                        'user' => [
                            'id',
                            'email'
                        ],
                        'created_at',
                        'updated_at'
                    ],
                ]
            ]);
    }

    /** @test */
    public function unauthenticated_users_can_not_list_articles()
    {
        $this->get('/api/articles')
            ->assertStatus(401);
    }
}
