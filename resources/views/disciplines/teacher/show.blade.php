@extends('layouts.app')

@section('title')
    Дисципліна {{ $discipline->forTeacher }}
@endsection

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col lg:flex-row mx-auto">
        <div class="font-museo-cyrl md:w-5/12 bg-blue-500 flex flex-col text-base sm:text-lg md:text-xl lg:text-3xl">
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white bg-blue-700 text-center">
                Дисципліна {{ $discipline->forTeacher }}
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white cursor-pointer"
                 data-menu-button="lessons">
                Заняття
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white cursor-pointer"
                 data-menu-button="homeworks">
                Домашні завдання
            </div>
            <hr>
            <div class="text-white px-8 py-4 md:border-solid border-b-2 border-white cursor-pointer"
                 data-menu-button="grades">
                Оцінки
            </div>
        </div>
        <div class="bg-blue-100 md:w-7/12 flex text-blue-800 font-gotham-pro">
            <div data-menu-section="lessons" class="px-2 sm:px-12 py-6 w-full space-y-4">
                Заняття
            </div>
            <div data-menu-section="lessons" class="px-2 sm:px-12 py-6 w-full space-y-4">
                Домашні завдання
            </div>
            <div data-menu-section="lessons" class="px-2 sm:px-12 py-6 w-full space-y-4">
                Оцінки
            </div>
        </div>
    </main>

    @include('layouts.footer')
@endsection

@push('scripts')
    <!-- Switch panels -->
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

        buttons.item(0).dispatchEvent(new Event('click'));
    </script>
    <!-- Model deleting -->
    <script>
        function deleteLesson(lesson) {
            try {
                return confirm(`Видалити заняття ${lesson}?`);
            } catch (e) {
                console.error(e);
            }

            return false;
        }

        function deleteHomework(homework) {
            try {
                return confirm(`Видалити домашнє завдання ${homework}?`);
            } catch (e) {
                console.error(e);
            }

            return false;
        }

        function deleteGrade(newsTitle) {
            try {
                return confirm(`Видалити новину ${newsTitle}?`);
            } catch (e) {
                console.error(e);
            }

            return false;
        }
    </script>
    <!-- Session message -->
    <script>
        @if(session()->has('message'))
        alert('{{ session('message') }}');
        @endif
    </script>
@endpush
