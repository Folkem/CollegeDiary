@extends('layouts.app')

@section('title')
    Заняття дисципліни {{ $lesson->discipline->subject }}
@endsection

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col pt-8 mx-auto">
        <div class="w-full p-4 italic text-center text-lg">
            {{ $lesson->created_at }} — {{ $lesson->lessonType->name }}
        </div>
        <div class="w-full p-4 text-base">
            {!! $lesson->description !!}
        </div>
    </main>

    @include('layouts.footer')
@endsection
