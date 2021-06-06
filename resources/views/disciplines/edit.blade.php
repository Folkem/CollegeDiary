@extends('layouts.app')

@section('title', 'Редагування дисципліни')

@section('body')
    @include('layouts.header')

    <!-- 1024px and above warning -->
    <div class="block lg:hidden flex flex-col w-full mt-32">
        <div class="m-auto font-gotham-pro text-red-500 text-center text-2xl sm:text-3xl md:text-4xl px-8">
            Через насичений інтерфейс, адмін-панель доступна лише при розширенні екрану
            <b>1024 px</b> (пікселя) і вище (іншими словами: лише з комп'ютерів та
            деяких планшетів).
            <br><br>
            Вибачте за незручності.
        </div>
    </div>

    <main class="max-w-1200 hidden lg:flex flex-col gap-6 mx-auto pt-20 text-blue-900">
        <div class="text-3xl font-gotham-pro-bold mx-auto">
            Редагування дисципліни
        </div>
        @if(session()->has('message'))
            <div class="text-xl text-center">
                {{ session('message') }}
            </div>
        @endif
        <form action="{{ route('disciplines.update', $discipline) }}" method="post"
              class="w-6/12 xl:w-5/12 mx-auto flex flex-col gap-4">
            @csrf
            @method('put')
            <div class="flex flex-col gap-2">
                <label for="subject" class="text-xl pl-3">
                    Предмет
                </label>
                <input type="text" required id="subject" name="subject" placeholder="Предмет"
                       class="rounded-full px-3 py-1 text-lg border border-solid border-blue-900"
                        value="{{ old('subject') ?: $discipline->subject }}">
                @error('subject')
                <div class="font-bold italic text-red-600 pl-3">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="group_id" class="text-xl pl-3">
                    Група
                </label>
                <select name="group_id" id="group_id" required
                        class="rounded-full px-3 py-1 text-lg bg-white border border-solid border-blue-900">
                    <option selected></option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}"
                            @if((old('group_id') ?: $discipline->group->id) == $group->id) selected @endif>
                            {{ $group->human_name }}
                        </option>
                    @endforeach
                </select>
                @error('group_id')
                <div class="font-bold italic text-red-600 pl-3">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="teacher_id" class="text-xl pl-3">
                    Викладач
                </label>
                <select name="teacher_id" id="teacher_id" required
                        class="rounded-full px-3 py-1 text-lg bg-white border border-solid border-blue-900">
                    <option selected></option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            @if((old('teacher_id') ?: $discipline->teacher->id) == $teacher->id) selected @endif>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
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
