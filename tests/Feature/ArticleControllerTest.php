<?php

namespace Tests\Feature;

use App\User;
use App\Article;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    protected $endpoint = '/api/articles';

    protected $articleJsonStructure = [
        'id', 'user_id', 'body', 'title', 'created_at', 'updated_at'
    ];

    /** @test */
    public function can_list_articles()
    {
        $user = factory(User::class)->create();

        $articles = factory(Article::class, 10)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user, 'api')
            ->json('GET', '/api/articles')
            ->assertStatus(200)
            ->assertJson([
                'data' => $articles->toArray()
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->articleJsonStructure
                ]
            ]);
    }

    /** @test */
    public function unauthenticated_users_can_not_list_articles()
    {
        $this->json('GET', '/api/articles')
            ->assertStatus(401);
    }

    /** @test */
    public function users_can_create_an_article()
    {
        $user = factory(User::class)->create();

        $article = factory(Article::class)->make([
            'user_id' => $user->id
        ])->toArray();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/articles', $article)
            ->assertStatus(201)
            ->assertJson([
                'data' => $article
            ])
            ->assertJsonStructure([
                'data' => $this->articleJsonStructure
            ]);

        $this->assertDatabaseHas('articles', $article);
    }

    /** @test */
    public function users_can_not_create_an_article_without_required_fields()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/articles')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'body']);
    }

    /** @test */
    public function users_can_update_their_articles()
    {
        $user = factory(User::class)->create();

        $article = factory(Article::class)->create([
            'user_id' => $user->id
        ])->toArray();

        $article['title'] = 'Updating the article title';

        $this->actingAs($user, 'api')
            ->json('PUT', "/api/articles/{$article['id']}", $article)
            ->assertStatus(200)
            ->assertJson([
                'data' => $article
            ])
            ->assertJsonStructure([
                'data' => $this->articleJsonStructure
            ]);

        $this->assertDatabaseHas('articles', $article);
    }

    /** @test */
    public function users_can_not_update_articles_from_other_users()
    {
        $user = factory(User::class)->create();
        $userTwo = factory(User::class)->create();

        $article = factory(Article::class)->create([
            'user_id' => $user->id
        ])->toArray();

        $article['title'] = 'Updating the article title';

        $this->actingAs($userTwo, 'api')
            ->json('PUT', "/api/articles/{$article['id']}", $article)
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_delete_their_articles()
    {
        $user = factory(User::class)->create();

        $article = factory(Article::class)->create([
            'user_id' => $user->id
        ])->toArray();

        $this->actingAs($user, 'api')
            ->json('DELETE', "/api/articles/{$article['id']}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function users_can_not_delete_articles_from_other_users()
    {
        $user = factory(User::class)->create();
        $userTwo = factory(User::class)->create();

        $article = factory(Article::class)->create([
            'user_id' => $user->id
        ])->toArray();

        $this->actingAs($userTwo, 'api')
            ->json('DELETE', "/api/articles/{$article['id']}")
            ->assertStatus(403);

        $this->assertDatabaseHas('articles', $article);
    }
}
