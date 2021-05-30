<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $newsList = News::query()->orderBy('created_at', 'desc')->get();
		  
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
        return view('news.show', compact('news'));
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
