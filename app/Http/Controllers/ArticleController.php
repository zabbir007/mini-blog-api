<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function mine(Request $request)
    {
        return response()->json($request->user()->articles()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'in:draft,published',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $data['slug'] = Str::slug($data['title']);
        $article = $request->user()->articles()->create($data);

        return response()->json($article, 201);
    }

    public function show(Request $request, $id)
    {
        $article = Article::where('user_id', $request->user()->id)->findOrFail($id);
        return response()->json($article);
    }

    public function update(Request $request, $id)
    {
        $article = Article::where('user_id', $request->user()->id)->findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            'status' => 'sometimes|in:draft,published',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $article->update($data);
        return response()->json($article);
    }

    public function destroy(Request $request, $id)
    {
        $article = Article::where('user_id', $request->user()->id)->findOrFail($id);
        $article->delete();
        return response()->json(['message' => 'Article deleted']);
    }
}
