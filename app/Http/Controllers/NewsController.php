<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $newsList = News::query()->orderBy('created_at', 'desc')->with('comments');

        if ($request->input('body') !== null) {
            $newsList = $newsList->where('body', 'like',
                '%' . $request->input('body') . '%');
        }
        if ($request->input('start-date') !== null) {
            $newsList = $newsList->whereDate('created_at', '>', $request->input('start-date'));
        }
        if ($request->input('end-date') !== null) {
            $newsList = $newsList->whereDate('created_at', '<', $request->input('end-date'));
        }
        $newsList = $newsList->with('tags')->get();
        if ($request->input('tags') !== null) {
            $tags = collect(explode(',', $request->input('tags')))->map(fn($tag) => trim($tag));
            $newsList = $newsList->filter(function ($news) use ($tags) {
                $retain = false;
                foreach ($tags as $tag) {
                    if ($news->tags->contains($tag, 'text')) {
                        $retain = true;
                        break;
                    }
                }

                return $retain;
            });
        }

        return view('news.index', compact('newsList'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(News $news)
    {
        $comments = $news->comments;
        $tags = $news->tags;

        return view('news.show', compact('news', 'comments', 'tags'));
    }

    public function edit(News $news)
    {
        //
    }

    public function update(Request $request, News $news)
    {
        //
    }

    public function destroy(News $news)
    {
        //
    }
}
