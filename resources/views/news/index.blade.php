@extends('layouts.app')

@section('title', 'Новини')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/news/index.css') }}">
@endsection

@section('body')
    @include('layouts.header')

    <section class="main-body">
        <div class="slider body">
            <div class="slider-body">
                @for($i = 0; $i < 4; $i++)
                <div class="slider-item">
                    <img src="{{ asset('media/static/main-cover.jpg') }}" alt="" class="image-0{{ $i }}">
                    <div class="slider-text">
                        <p>В ПГФК, наші студенти <br> мають свободу вибору <br> їхньої спеціальності
                        </p>
                    </div>
                </div>
                @endfor
            </div>
        </div>
        <div class="about body">
            <span class="outcome">Плоди нашої <br> роботи</span>
            <hr class="blue-line" width="6.5" size="100">
            <p>
                Зібратися разом - це початок, <br>
                Триматися разом - це прогрес, <br>
                Працювати разом - це успіх. <br>
            </p>
            <button class="pgkd-news">новини пгфк</button>
        </div>
    </section>

    @foreach($newsList as $news)
        <div class="news-element">
            <figure class="news-element-placeholder"><img src="" alt="" class="news-element-image"></figure>
            <h2 class="news-element-title">{{ $news->title }}</h2>
            <p class="news-element-text">{{ $news->body }}</p>
        </div>
    @endforeach

    @include('layouts.footer')
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/news/index.js') }}"></script>
@endsection
