@extends('layouts.app')

@section('title', 'Редагування групи')

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col gap-6 mx-auto pt-20 text-blue-900">
        <div class="text-3xl font-gotham-pro-bold mx-auto">
            Редагування групи
        </div>
        @if(session()->has('message'))
            <div class="text-xl text-center">
                {{ session('message') }}
            </div>
        @endif
        <form action="{{ route('groups.update', $group) }}" method="post"
              class="w-6/12 xl:w-5/12 mx-auto flex flex-col gap-4">
            @csrf
            @method('put')
            <div class="flex flex-col gap-2">
                <label for="speciality_id" class="text-xl pl-3">
                    Напрям
                </label>
                <select name="speciality_id" id="speciality_id" required
                        class="rounded-full px-3 py-1 text-lg bg-white border border-solid border-blue-900">
                    <option selected></option>
                    @foreach($specialities as $speciality)
                        <option value="{{ $speciality->id }}"
                                @if((old('speciality_id') ?: $group->speciality->id) == $speciality->id) selected @endif>
                            {{ $speciality->humanName }}
                        </option>
                    @endforeach
                </select>
                @error('speciality_id')
                <div class="font-bold italic text-red-600 pl-3">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="year" class="text-xl pl-3">
                    Рік навчання
                </label>
                <input type="number" required id="year" name="year" placeholder="Рік навчання"
                       class="rounded-full px-3 py-1 text-lg border border-solid border-blue-900"
                        value="{{ old('year') ?: $group->year }}">
                @error('year')
                <div class="font-bold italic text-red-600 pl-3">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="group_number" class="text-xl pl-3">
                    Номер групи
                </label>
                <input type="number" required id="group_number" name="group_number" placeholder="Номер групи"
                       class="rounded-full px-3 py-1 text-lg border border-solid border-blue-900"
                        value="{{ old('group_number') ?: $group->number }}">
                @error('group_number')
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
