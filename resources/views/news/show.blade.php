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
      <section class="show-wrapper body">
         <figure class="show-news-placeholder">
            <img src="{{ asset('media/static/main-cover.jpg') }}" alt="">
         </figure>
         <article class="show-title">
            <div> {{ $news->title }} </div>
         </article>
         <div class="content-wrapper">
            <div class="content-body">
               <div class="content">{{ $news->body }}</div>
            </div>
            <div class="attribute-body">
               <div class="attribute-tag-wrapper">
                  <div class="attribute-tag">
                     <div>{{ $news->updated_at->diffForHumans() }}</div>
                  </div>
               </div>
               <div class="attribute-date-wrapper">
                  <div class="attribute-date">
                     <div>{{ $news->created_at->diffForHumans() }}</div>
                  </div>
               </div>
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
