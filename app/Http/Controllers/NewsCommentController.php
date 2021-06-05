<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsCommentController extends Controller
{
    public function store(Request $request, News $news): RedirectResponse
    {
        $request->validate([
            'body' => 'required|between:10,16384',
        ]);

        NewsComment::query()->create([
            'user_id' => 1,
            'news_id' => $news->id,
            'body' => strip_tags($request->input('body'), [
                'strong', 'i', 'ul', 'ol', 'li', 'h1', 'h2', 'a', 'p', 'blockquote',
            ]),
        ]);

        return back();
    }

    public function destroy(NewsComment $comment): RedirectResponse
    {
        $comment->delete();

        return back();
    }
}
