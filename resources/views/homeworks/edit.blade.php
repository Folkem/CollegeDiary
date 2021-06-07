@extends('layouts.app')

@section('title', 'Оновлення домашнього завдання')

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col gap-6 mx-auto pt-20 text-blue-900">
        <div class="text-xl sm:text-2xl md:text-3xl font-gotham-pro-bold mx-auto text-center">
            Оновлення домашнього завдання {{ $homework->created_at }} — {{ $homework->ending_at }}
        </div>
        @if(session()->has('message'))
            <div class="text-xl text-center">
                {{ session('message') }}
            </div>
        @endif
        <form action="{{ route('homeworks.update', $homework) }}" method="post"
              class="w-9/12 xl:w-8/12 mx-auto flex flex-col gap-4">
            @csrf
            @method('put')
            <div class="flex flex-col gap-2">
                <label for="ending_at" class="text-xl pl-3">
                    Дата сдачі
                </label>
                <input type="datetime-local" required id="ending_at" name="ending_at" placeholder="Дата сдачі"
                       class="rounded-full px-3 py-1 text-lg border border-solid border-blue-900 bg-white w-full"
                       value="{{ old('ending_at') ?: $homework->ending_at->format('Y-m-d\\TH:i') }}">
                @error('ending_at')
                <div class="font-bold italic text-red-600 pl-3">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="description" class="text-xl pl-3">
                    Опис
                </label>
                <textarea name="description" id="description" cols="30" rows="10">
                    {!! old('description') ?: $homework->description !!}
                </textarea>
                @error('description')
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
            .create(document.querySelector('#description'), {
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
