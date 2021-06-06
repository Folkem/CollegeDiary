@extends('layouts.app')

@section('title', 'Редагування викладача')

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 hidden lg:flex flex-col gap-6 mx-auto pt-20 text-blue-900">
        <div class="text-3xl font-gotham-pro-bold mx-auto">
            Редагування викладача
        </div>
        @if(session()->has('message'))
            <div class="text-xl text-center">
                {{ session('message') }}
            </div>
        @endif
        <form action="{{ route('teachers.update', $teacher) }}" method="post"
              class="w-6/12 xl:w-5/12 mx-auto flex flex-col gap-4">
            @csrf
            @method('put')
            <div class="flex flex-col gap-2">
                <label for="email" class="text-xl pl-3">
                    Пошта
                </label>
                <input type="email" required id="email" name="email" placeholder="Пошта"
                       class="rounded-full px-3 py-1 text-lg border border-solid border-blue-900"
                        value="{{ old('email') ?: $teacher->email }}">
                @error('email')
                <div class="font-bold italic text-red-600 pl-3">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="name" class="text-xl pl-3">
                    Ім'я
                </label>
                <input type="text" required id="name" name="name" placeholder="Ім'я"
                       class="rounded-full px-3 py-1 text-lg border border-solid border-blue-900"
                        value="{{ old('name') ?: $teacher->name }}">
                @error('name')
                <div class="font-bold italic text-red-600 pl-3">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 p-2 text-white text-lg font-bold">
                    ЗБЕРЕГТИ
                </button>
            </div>
        </form>
    </main>

    @include('layouts.footer')
@endsection