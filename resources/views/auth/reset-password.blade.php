@extends('layouts.app')

@section('title', 'Оновлення паролю')

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col mx-auto text-center">
        <div class="text-xl md:text-2xl xl:text-3xl font-bold my-6">
            Оновлення паролю
        </div>
        @if($errors->any())
            <div class="bg-red-300 rounded-lg p-4 my-2 w-10/12 sm:w-9/12 md:w-8/12 lg:w-1/2 mx-auto">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('password.update') }}" method="post" class="text-lg flex flex-col gap-4 w-10/12
            sm:w-9/12 md:w-8/12 lg:w-1/2 mx-auto">
            @csrf
            <input type="hidden" value="{{ $token }}" name="token">
            <div class="flex flex-col gap-2">
                <label for="email" class="pl-4 text-lg text-left">
                    Пошта
                </label>
                <input type="email" required name="email" id="email" class="px-4 py-2 rounded-full
                    border border-solid border-black text-base" placeholder="Пошта">
            </div>
            <div class="flex flex-col gap-2">
                <label for="password" class="pl-4 text-lg text-left">
                    Пароль
                </label>
                <input type="password" required name="password" id="password" class="px-4 py-2 rounded-full
                    border border-solid border-black text-base" placeholder="Пароль">
            </div>
            <div class="flex flex-col gap-2">
                <label for="password_confirmation" class="pl-4 text-lg text-left">
                    Підтвердження паролю
                </label>
                <input type="password" required name="password_confirmation" id="password_confirmation"
                       class="px-4 py-2 rounded-full border border-solid border-black text-base"
                       placeholder="Повторіть пароль">
            </div>
            <div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 font-bold text-white p-2">
                    Оновити пароль
                </button>
            </div>
        </form>
    </main>

    @include('layouts.footer')
@endsection
