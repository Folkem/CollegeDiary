@extends('layouts.app')

@section('title')
    Дисципліни
@endsection

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col lg:flex-row mx-auto">
        <div class="font-museo-cyrl lg:w-5/12 bg-blue-500 flex flex-col text-base sm:text-lg md:text-xl lg:text-3xl">
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white bg-blue-700 text-center">
                Дисципліни
            </div>
        </div>
        <div class="bg-blue-100 lg:w-7/12 flex flex-col gap-4 px-4 text-blue-800 font-gotham-pro text-center pb-8">
            @if($disciplines->count() === 0)
                <div class="italic m-auto">
                    Дисциплін нема.
                </div>
            @else
                <div class="w-full flex flex-row gap-2 font-bold text-xl mt-8">
                    <div class="w-6/12">
                        Предмет
                    </div>
                    <div class="w-3/12">
                        Викладач
                    </div>
                    <div class="w-3/12">
                        Посилання
                    </div>
                </div>
                @foreach($disciplines as $discipline)
                    <div class="w-full flex flex-row gap-2 text-lg">
                        <div class="w-6/12 self-center">
                            {{ $discipline->subject }}
                        </div>
                        <div class="w-3/12 self-center">
                            {{ $discipline->teacher->name }}
                        </div>
                        <div class="w-3/12 font-bold italic self-center">
                            <a href="{{ route('disciplines.teacher.show', $discipline) }}">Перейти</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </main>

    @include('layouts.footer')
@endsection
