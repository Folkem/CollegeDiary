@extends('layouts.app')

@section('title', 'Новини')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/news/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/news/slider.css') }}">
@endsection

@section('body')
    @include('layouts.header')

    <main class="h-full w-full">
        <section>

            <div class="outline-none slider max-w-1200 mx-auto">
                <div class="slider-body">
                    @foreach($newsList->skip(3)->take(4) as $news)
                        <div class="slider-item h-80 sm:h-96 md:h-500 xl:h-820">
                            <img src="{{ asset("media/news-covers/$news->image_path") }}"
                                 alt="{{ $news->title }}">
                            <div class="slider-text font-gotham-pro-bold text-lg sm:text-2xl
                                md:text-3xl xl:text-4xl text-white mb-12">
                                <p>{{ $news->title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- todo: think about the need of this part
            <div class="about max-w-1200 mx-auto">
                <div class="font-museo-cyrl text-4xl w-5/12">Плоди нашої роботи</div>
                <div class="w-4/12 border-l-6 border-blue-400 border-solid p-5">
                    <p class="font-bitter-italic text-lg leading-5">
                        Зібратися разом - це початок,<br>
                        Триматися разом - це прогрес,<br>
                        Працювати разом - це успіх.<br>
                    </p>
                </div>
                <div class="w-3/12 text-white text-3xl lowercase bg-blue-400">новини пгфк</div>
            </div>
            --}}
        </section>
        <section class="mt-8">
            <div class="max-w-1200 mx-auto lg:flex flex-row gap-8 justify-between">
                <form action="{{ route('news.index') }}" method="get"
                      class="bg-blue-400 h-fit w-1/3 p-4 hidden lg:flex flex-col gap-4">
                    <div class="space-y-2">
                        <label for="body" class="text-white font-bold text-lg font-gotham-pro">Пошук по змісту: </label>
                        <input type="text" placeholder="Пошук новини..." id="body" name="body"
                               value="{{ request('body') }}"
                               class="news-search w-full p-2 font-gotham-pro text-sm rounded-3xl">
                    </div>
                    <div class="space-y-2">
                        <label for="start-date" class="text-white font-bold text-lg font-gotham-pro">Пошук по початковій даті: </label>
                        <input type="date" id="start-date" name="start-date" value="{{ request('start-date') }}"
                               class="datepicker-from font-gotham-pro w-full box-border p-2 rounded-3xl">
                    </div>
                    <div class="space-y-2">
                        <label for="end-date" class="text-white font-bold text-lg font-gotham-pro">Пошук по кінцевій даті: </label>
                        <input type="date" id="end-date" name="end-date" value="{{ request('end-date') }}"
                               class="datepicker-to font-gotham-pro w-full box-border p-2 rounded-3xl">
                    </div>
                    <div class="space-y-2">
                        <label for="tags" class="text-white font-bold text-lg font-gotham-pro">Пошук по тегам (через кому): </label>
                        <input type="text" placeholder="Пошук по тегу... (розклад, група тощо)"
                               id="tags" name="tags" value="{{ request('tags') }}"
                               class="tag-search w-full p-2 font-gotham-pro text-sm rounded-3xl">
                    </div>
                    <button type="reset" class="w-full p-2 rounded-xl bg-gray-500 hover:bg-gray-600
                        text-lg font-bold text-white">
                        Очистити пошук
                    </button>
                    <button type="submit" class="w-full p-2 rounded-xl bg-green-500 hover:bg-green-600
                        text-lg font-bold text-white">
                        Пошук
                    </button>
                </form>
                <div class="lg:w-2/3 px-8 flex gap-y-8 flex-wrap justify-between">
                    {{-- todo: move these styles to tailwind --}}
                    @foreach($newsList as $i => $news)
                        <div class="w-full md:w-5/12 lg:w-6/12
                            lg:{{ $i % 2 == 0 ? 'pr-4' : 'pl-4' }}
                            ">
                            <img src="{{ asset("media/news-covers/$news->image_path") }}" alt="">
                            <h2 class="news-element-title">
                                <a href="{{ route('news.show', $news) }}">{{ $news->title }}</a>
                            </h2>
                            <p class="news-element-text">{{ \Illuminate\Support\Str::limit($news->body, 200) }}</p>
                            <p class="news-element-date"> {{ $news->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    @include('layouts.footer')
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/news/slider.js') }}"></script>
@endsection
