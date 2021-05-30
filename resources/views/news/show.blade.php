@extends('layouts.app')

@section('title')
   {{ $news->title }}
@endsection

@section('stylesheets')
   <link rel="stylesheet" href="{{ asset('css/news/show.css') }}">
@endsection

@section('body')
   @include('layouts.header')
   <main class="show-main">
      <div class="show-wrapper body">
         <figure class="show-news-placeholder">
            <img src="{{ asset('media/static/main-cover.jpg') }}" alt="">

         </figure>
         <article class="">
            <div>{{ $news->title }}</div>
         </article>
         <div>
            <div>{{ $news->body }}</div>
         </div>
         <div>
            <div>{{ $news->created_at }}</div>
         </div>
         <div>
            <div>{{ $news->updated_at }}</div>
         </div>
      </div>
   </main>
   @include('layouts.footer')
@endsection

@section('scripts')
   <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
   <script src="{{ asset('js/slick.min.js') }}"></script>
   <script src="{{ asset('js/news/index.js') }}"></script>
@endsection
