<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    public function create()
    {
        return view('news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => [
                'required', 'string', 'between:5,255',
                Rule::unique('news', 'title'),
            ],
            'body' => [
                'required', 'between:10,16386',
            ],
            'image' => [
                'nullable', 'file', 'mimetypes:image/*', 'max:256',
            ],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');

            $imagePath = $imageFile->store("media/news-covers", ['disk' => 'public']);
            info($imagePath);
        }

        News::query()->create([
            'title' => $request->input('title'),
            'body' => strip_tags($request->input('body'), [
                'strong', 'i', 'ul', 'ol', 'li', 'h1', 'h2', 'a', 'p', 'blockquote',
            ]),
            'image_path' => $imagePath,
        ]);

        return back()->with('message', 'Новина успішно додана.');
    }

    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $request->validate([
            'title' => [
                'required', 'string', 'between:5,255',
                Rule::unique('news', 'title')->ignore($news->title, 'title'),
            ],
            'body' => [
                'required', 'between:10,16386',
            ],
            'image' => [
                'nullable', 'file', 'mimetypes:image/*', 'max:256',
            ],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');

            $imagePath = $imageFile->store("media/news-covers", ['disk' => 'public']);
        }

        $news->update([
            'title' => $request->input('title'),
            'body' => strip_tags($request->input('body'), [
                'strong', 'i', 'ul', 'ol', 'li', 'h1', 'h2', 'a', 'p', 'blockquote',
            ]),
            'image_path' => $imagePath,
        ]);

        return back()->with('message', 'Новина успішно оновлена.');
    }

    public function destroy(News $news): RedirectResponse
    {
        try {
            $news->delete();
            return back();
        } catch (Exception $exception) {
            return back()->with('message', 'Серверна помилка');
        }
    }
}
