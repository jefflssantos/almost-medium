<?php

namespace App\Http\Controllers;

use Cache;
use App\Article;
use Illuminate\Http\Request;
use App\Http\Requests\StoreArticle;
use App\Http\Requests\UpdateArticle;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $key = 'articles_page_' . $request->page ?? 1;

        return Cache::tags('articles')
            ->rememberForever($key, function () {
                return Article::latest('id')->paginate();
            });
    }

    public function store(StoreArticle $request)
    {
        $data = $request->validated() + ['user_id' => auth()->id()];

        $article = Article::create($data);

        Cache::tags('articles')->flush();

        return response()->json(['data' => $article], 201);
    }

    public function show($id)
    {
        return Cache::rememberForever("article_{$id}", function () use ($id) {
                return Article::findOrFail($id);
            });
    }

    public function update(UpdateArticle $request, Article $article)
    {
        $this->authorize('update', $article);

        $article->update($request->validated());

        Cache::forget("article_{$article->id}");

        return ['data' => $article];
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        Cache::forget("article_{$article->id}");

        return response()->json(null, 204);
    }
}
