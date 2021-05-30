@extends('layouts.app')

@section('title')
    {{ $news->title }}
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/news/show.css') }}">
@endsection

@section('body')
    @include('layouts.header')

    <div>
        <div>Title: </div>
        <div>{{ $news->title }}</div>
    </div>
    <div>
        <div>Body: </div>
        <div>{{ $news->body }}</div>
    </div>
    <div>
        <div>Created at: </div>
        <div>{{ $news->created_at }}</div>
    </div>
    <div>
        <div>Updated at: </div>
        <div>{{ $news->updated_at }}</div>
    </div>

    @include('layouts.footer')
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/news/index.js') }}"></script>
@endsection
