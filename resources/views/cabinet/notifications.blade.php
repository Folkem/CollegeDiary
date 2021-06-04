@extends('layouts.app')

@section('title', 'Сповіщення')

@section('stylesheets')

@endsection

@section('body')
    @include('layouts.header')
    <main class="w-full h-full">
        <div class="container max-w-1200 flex flex-col mx-auto">
            <div class="head py-10 text-center text-white text-2xl font-gotham-pro-bold bg-blue-600 ">Панель сповіщень
            </div>
            <div class="notification-body ">
                @for($i = 0; $i < 3; $i++)
                    <div class="notification-item h-16 w-full flex flex-row items-center bg-blue-200 border-solid border-b-2 border-blue-600">
                        <div class="icon-body pl-10">
                            <figure class="icon-placeholder w-9 h-9 rounded-full bg-blue-600">
                                <img src="" alt="" class="icon ">
                            </figure>
                        </div>
                        <div class="title-to-date w-full pr-10 flex justify-between ">
                            <span class="notification-title pl-5 font-gotham-pro-bold">Вам поставили оцінку з Дисципліни "Web-Design"</span>
                            <span class="block font-gotham-pro-bold">2021.05.06</span>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </main>

    @include('layouts.footer')
@endsection
