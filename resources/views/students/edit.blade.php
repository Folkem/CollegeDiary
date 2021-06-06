@extends('layouts.app')

@section('title', 'Редагування студента')

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col gap-6 mx-auto pt-20 text-blue-900">
        <div class="text-3xl font-gotham-pro-bold mx-auto">
            Редагування студента
        </div>
        @if(session()->has('message'))
            <div class="text-xl text-center">
                {{ session('message') }}
            </div>
        @endif
        <form action="{{ route('students.update', $student) }}" method="post"
              class="w-6/12 xl:w-5/12 mx-auto flex flex-col gap-4">
            @csrf
            @method('put')
            <div class="flex flex-col gap-2">
                <label for="email" class="text-xl pl-3">
                    Пошта
                </label>
                <input type="email" required id="email" name="email" placeholder="Пошта"
                       class="rounded-full px-3 py-1 text-lg border border-solid border-blue-900"
                        value="{{ old('email') ?: $student->email }}">
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
                        value="{{ old('name') ?: $student->name }}">
                @error('name')
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
                            @if((old('group_id') ?: $student->group->id) == $group->id) selected @endif>
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
            <div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 p-2 text-white text-lg font-bold">
                    ЗБЕРЕГТИ
                </button>
            </div>
        </form>
    </main>

    @include('layouts.footer')
@endsection
