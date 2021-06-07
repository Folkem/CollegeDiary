@extends('layouts.app')

@section('title', 'Додавання заняття')

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col gap-6 mx-auto pt-20 text-blue-900">
        <div class="text-xl sm:text-2xl md:text-3xl font-gotham-pro-bold mx-auto text-center">
            Додавання заняття до дисципліни {{ $discipline->forTeacher }}
        </div>
        @if(session()->has('message'))
            <div class="text-xl text-center">
                {{ session('message') }}
            </div>
        @endif
        <form action="{{ route('lessons.store', $discipline) }}" method="post"
              class="w-9/12 xl:w-8/12 mx-auto flex flex-col gap-4">
            @csrf
            <div class="flex flex-col gap-2">
                <label for="lesson_type_id" class="text-xl pl-3">
                    Тип заняття
                </label>
                <select name="lesson_type_id" id="lesson_type_id" required
                        class="rounded-full px-3 py-1 text-lg bg-white border border-solid border-blue-900">
                    <option selected></option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}"
                                @if(old('lesson_type_id') == $type->id) selected @endif>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('lesson_type_id')
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
                    {!! old('description') !!}
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
