@extends('layouts.app')

@section('title', 'Редагування новини')

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 hidden lg:flex flex-col gap-6 mx-auto pt-20 text-blue-900">
        <div class="text-3xl font-gotham-pro-bold mx-auto">
            Редагування новини
        </div>
        @if(session()->has('message'))
            <div class="text-xl text-center">
                {{ session('message') }}
            </div>
        @endif
        <form action="{{ route('news.update', $news) }}" method="post" enctype="multipart/form-data"
              class="w-9/12 xl:w-8/12 mx-auto flex flex-col gap-4">
            @csrf
            @method('put')
            <div class="flex flex-col gap-2">
                <label for="title" class="text-xl pl-3">
                    Назва
                </label>
                <input type="text" required id="title" name="title" placeholder="Назва"
                       class="rounded-full px-3 py-1 text-lg border border-solid border-blue-900"
                       value="{{ old('title') ?: $news->title }}">
                @error('title')
                <div class="font-bold italic text-red-600 pl-3">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="image" class="text-xl px-3 border-2 border-solid rounded-full border-black">
                    Нове зображення
                </label>
                <input type="file" id="image" name="image" accept="image/*"
                       class="hidden">
                @error('image')
                <div class="font-bold italic text-red-600 pl-3">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="body" class="text-xl pl-3">
                    Контент
                </label>
                <textarea name="body" id="body" cols="30" rows="10">
                    {!! old('body') ?: $news->body !!}
                </textarea>
                @error('body')
                <div class="font-bold italic text-red-600 pl-3">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 p-2 text-white text-lg font-bold">
                    ЗБЕРЕГТИ
                </button>
            </div>
        </form>
    </main>

    @include('layouts.footer')
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#body'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
                heading: {
                    options: [
                        {model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph'},
                        {model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'},
                        {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'},
                        {model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'},
                    ]
                },
                link: {
                    addTargetToExternalLinks: true,
                }
            })
            .catch(console.error);
    </script>
@endpush
