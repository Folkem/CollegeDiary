@extends('layouts.app')

@section('title')
    Домашня робота дисципліни {{ $homework->discipline->subject }}
@endsection

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col pt-8 mx-auto">
        <div class="w-full p-4 italic text-center text-lg">
            {{ $homework->created_at }} — {{ $homework->ending_at }}
        </div>
        <div class="w-full p-4 text-base">
            {!! $homework->description !!}
        </div>
    </main>

    @include('layouts.footer')
@endsection
