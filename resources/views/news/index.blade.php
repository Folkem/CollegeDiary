@extends('layouts.app')

@section('title', 'Новини')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/news/index.css') }}">
@endsection

@section('body')
    @include('layouts.header')

    <main class="main">
        <section class="main-body">
            <div class="slider body">
                <div class="slider-body">
                    @for($i = 0; $i < 4; $i++)
                        <div class="slider-item">
                            <img src="{{ asset('media/static/main-cover.jpg') }}" alt="" class="image-0{{ $i }}">
                            <div class="slider-text">
                                <p>В ПГФК, наші студенти<br>мають свободу вибору<br>їхньої спеціальності</p>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="about body">
                <span class="outcome">Плоди нашої <br> роботи</span>
                <div class="about-text">
                    <p>
                        Зібратися разом - це початок,<br>
                        Триматися разом - це прогрес,<br>
                        Працювати разом - це успіх.<br>
                    </p>
                </div>
                <button class="pgkd-news">новини пгфк</button>
            </div>
        </section>
        <section class="news">
            <div class="news-body body">
                <div class="search-column">
                    <div class="search-panel">
                        <div class="search-wrapper">
                            <label for=""><input type="text" placeholder="Пошук новини..." class="news-search"></label>
                            <label for=""><input type="date" placeholder="Рік.Місяць.День"
                                                 class="datepicker-from"></label>
                            <label for=""><input type="date" placeholder="Рік.Місяць.День"
                                                 class="datepicker-to"></label>
                            <label for=""><input type="text" placeholder="Пошук по тегу..." class="tag-search"></label>
                        </div>
                    </div>
                </div>
                <div class="news-column">
                    @foreach($newsList as $news)
                        <div class="news-element">
                            <figure class="news-element-placeholder">
                                <img src="{{ asset('media/static/main-cover.jpg') }}" alt="" class="news-element-image">
                            </figure>
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
    <script src="{{ asset('js/news/index.js') }}"></script>
@endsection
