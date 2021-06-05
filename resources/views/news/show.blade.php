@extends('layouts.app')

@section('title')
    {{ $news->title }}
@endsection

@section('body')
    @include('layouts.header')
    <main>
        <section class="mx-auto max-w-1200">
            <figure>
                <img src="{{ asset("media/news-covers/$news->image_path") }}"
                     alt="{{ $news->title }}" class="object-contain w-full h-full">
            </figure>
            <div
                class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-gotham-pro-bold mt-8 lg:mt-12 lg:mb-4
                        font-bold text-center border-solid border-0 border-l-6 border-blue-600 px-6 ml-4 sm:ml-0">
                {{ $news->title }}
            </div>
            <div class="flex flex-col">
                <div class="p-2 font-bold italic text-xl text-center">
                    <div>{{ $news->created_at->diffForHumans() }}</div>
                </div>
                <div class="p-8 bg-blue-200 rounded-2xl text-base font-gotham-pro">
                    {{ $news->body }}
                </div>
                <div class="flex flex-row justify-between mt-4">
                    <div class="flex flex-row flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <div class="bg-blue-400 p-2 rounded-2xl text-base text-white font-bold">
                                {{-- todo: link to the news.index with specified tag --}}
                                <div>#{{ $tag->text }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div data-role="comment-section" class="mt-12 lg:rounded-2xl font-gotham-pro bg-blue-200 overflow-hidden">
                <div class="p-4">
                    @if($comments->count() == 0)
                        <div class="text-center font-bold text-white py-12">
                            Будьте першими, хто залишить тут коментар!
                        </div>
                    @else
                        <div class="flex flex-col gap-4 p-2">
                            @foreach($comments as $comment)
                                <div class="bg-white p-4 rounded-xl sm:flex flex-row gap-6">
                                    <div class="w-12 box-border hidden sm:block">
                                        <div class="w-12 h-12 rounded-full box-content overflow-hidden">
                                            <img src="{{ asset('media/avatars/' . $comment->user->avatar_path) }}"
                                                 alt="Аватар користувача {{ $comment->user->name }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                    <div class="space-y-4 w-full">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <div class="space-y-2">
                                                <div class="font-gotham-pro-bold text-blue-800">
                                                    {{ $comment->user->name }}
                                                </div>
                                                <div class="font-gotham-pro text-blue-600">
                                                    {{ \Illuminate\Support\Str::ucfirst(__($comment->user->role->name)) }}
                                                    @if($comment->user->role->name === 'student')
                                                        {{ $comment->user->group->human_name }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:mt-0">
                                                <div class="sm:text-white font-gotham-pro-bold rounded-xl sm:py-1 sm:px-2
                                                        sm:bg-blue-700 h-auto">
                                                    {{ $comment->created_at }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="font-gotham-pro leading-5">
                                            {!! $comment->body !!}
                                        </div>
                                        @auth
                                            @if($comment->user->id === auth()->id() ||
                                                auth()->user()->role->name === 'admin')
                                                <div class="text-right text-blue-700">
                                                    <form action="{{ route('news.comment.destroy', $comment) }}"
                                                          method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="italic">
                                                            Видалити
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="p-4 bg-blue-600 h-fit">
                    @if(auth()->check())
                        <form action="{{ route('news.comment.store', $news) }}" method="post" class="space-y-3">
                            @csrf
                            <label for="comment-body" class="text-white font-bold text-lg">Ваш коментар: </label>
                            <textarea name="body" id="comment-body" class="h-full bg-white"></textarea>
                            <button type="submit"
                                    class="w-full p-4 text-white font-gotham-pro-bold bg-green-500 hover:bg-green-600">
                                Відправити коментар
                            </button>
                        </form>
                    @else
                        <div class="text-white font-gotham-pro-bold text-center text-lg">
                            Вам потрібно бути авторизованим, щоб залишити коментар.
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
    @include('layouts.footer')
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/news/slider.js') }}"></script>
    @auth
        <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
        <script type="module">

            ClassicEditor
                .create(document.querySelector('#comment-body'), {
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
    @endauth
@endpush
