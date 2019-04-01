<?php

namespace App\Policies;

use App\User;
use App\Article;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Article $article)
    {
        return $user->id == $article->user_id;
    }

    public function delete(User $user, Article $article)
    {
        return $user->id == $article->user_id;
    }
}
