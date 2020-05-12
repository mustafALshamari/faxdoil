<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use App\Http\Resources\Admin\ArticleResource;
use Exception;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function addArticle(AddArticleRequest $request)
    {
        try {

            Article::create($request->validated());

            return response()->json([
                'success' => 'successfully created your article'
            ], 200);

        }catch (Exception $e){

            return response()->json(['error' => 'something went wrong!'], 500);
        }
    }

    public function showArticle()
    {
        $article = Article::all()->first();

        return response()->json([
            'article' => new ArticleResource($article),
        ], 200);
    }

    public function updateArticle(UpdateArticleRequest $request, Article $article)
    {
        try {

            $article->update($request->validated());

            return response()->json([
                'success' => 'successfully updated your article'
            ], 200);

        }catch (Exception $e){

            return response()->json(['error' => 'something went wrong!'], 500);
        }

    }
}
