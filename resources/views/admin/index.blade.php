@extends('layouts.app')

@section('title', 'Адмін-панель')

@section('body')
    @include('layouts.header')

    <div class="block lg:hidden flex flex-col w-full mt-32">
        <div class="m-auto font-gotham-pro text-red-500 text-center text-2xl sm:text-3xl md:text-4xl px-8">
            Через насичений інтерфейс, адмін-панель доступна лише при розширенні екрану
            <b>1024 px</b> (пікселя) і вище (іншими словами: лише з комп'ютерів та
            деяких планшетів).
            <br><br>
            Вибачте за незручності.
        </div>
    </div>
    <main class="max-w-1200 hidden lg:flex flex-col lg:flex-row mx-auto">
        <div class="font-museo-cyrl md:w-5/12 bg-blue-500 flex flex-col text-base sm:text-lg md:text-xl lg:text-3xl">
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white bg-blue-700 text-center">
                Панель адміністратора
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white bg-white text-blue-900 cursor-pointer"
                 data-menu-button="students">
                Студенти
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white cursor-pointer"
                 data-menu-button="teachers">
                Викладачі
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white cursor-pointer"
                 data-menu-button="schedules">
                Розклади
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white cursor-pointer"
                 data-menu-button="disciplines">
                Дисципліни
            </div>
            <hr>
            <div class="text-white px-8 py-4 md:border-solid border-b-2 border-white cursor-pointer"
                 data-menu-button="news">
                Новини
            </div>
        </div>
        <div class="bg-blue-100 md:w-7/12 flex">
            <div data-menu-section="students" class="px-2 sm:px-12 py-6 w-full">
                Студенти
            </div>
            <div data-menu-section="teachers" class="px-2 sm:px-12 py-6 hidden w-full">
                Викладачі
            </div>
            <div data-menu-section="schedules" class="px-2 sm:px-12 py-6 hidden w-full">
                Розклади
            </div>
            <div data-menu-section="disciplines" class="px-2 sm:px-12 py-6 hidden w-full">
                Дисципліни
            </div>
            <div data-menu-section="news" class="px-2 sm:px-12 py-6 hidden w-full">
                Новини
            </div>
        </div>
    </main>

    @include('layouts.footer')
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <script>
        const buttons = document.querySelectorAll('[data-menu-button]');
        const sections = document.querySelectorAll('[data-menu-section]');
        buttons.forEach((button, key) => {
            button.addEventListener('click', () => {
                buttons.forEach((button, key) => {
                    button.classList.toggle('bg-white', false);
                    button.classList.toggle('text-blue-900', false);
                    sections.item(key).classList.toggle('hidden', true);
                });
                button.classList.toggle('bg-white', true);
                button.classList.toggle('text-blue-900', true);
                sections.item(key).classList.toggle('hidden', false);
            });
        });
    </script>
    <script>
        //...
    </script>
@endpush
