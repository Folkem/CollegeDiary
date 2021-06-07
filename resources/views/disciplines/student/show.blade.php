@extends('layouts.app')

@section('title')
    {{ $discipline->forStudent }}
@endsection

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col lg:flex-row mx-auto">
        <div class="font-museo-cyrl lg:w-5/12 bg-blue-500 flex flex-col text-base sm:text-lg md:text-xl lg:text-3xl">
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white bg-blue-700 text-center">
                {{ $discipline->forStudent }}
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
        <div class="bg-blue-100 lg:w-7/12 flex text-blue-800 font-gotham-pro">
            <div data-menu-section="lessons" class="px-2 sm:px-12 py-6 w-full space-y-4 space-y-4">
                @if($lessons->count() === 0)
                    <div class="font-bold text-center italic text-xl">
                        Занять нема.
                    </div>
                @else
                    <div class="font-bold text-center text-xl">
                        Заняття
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-row text-center font-bold text-lg">
                            <div class="w-6/12 sm:w-7/12 self-center break-words">
                                Опис
                            </div>
                            <div class="w-3/12 self-center break-words">
                                Тип заняття
                            </div>
                            <div class="w-3/12 sm:w-2/12 self-center break-words">
                                Дата публікації
                            </div>
                        </div>
                        @foreach($lessons as $lesson)
                            <div class="flex flex-row">
                                <div class="w-6/12 sm:w-7/12 self-center break-words">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($lesson->description, ['p']). 100) !!}
                                </div>
                                <div class="w-3/12 text-center self-center break-words">
                                    {{ $lesson->lessonType->name }}
                                </div>
                                <div class="w-3/12 sm:w-2/12 text-center self-center break-words">
                                    {{ $lesson->created_at }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div data-menu-section="homeworks" class="px-2 sm:px-12 py-6 w-full space-y-4">
                @if($homeworks->count() === 0)
                    <div class="font-bold text-center italic text-xl">
                        Домашніх завдань нема.
                    </div>
                @else
                    <div class="font-bold text-center text-xl">
                        Домашні завдання
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-row text-center font-bold text-lg">
                            <div class="break-words w-6/12 sm:w-7/12 self-center">
                                Опис
                            </div>
                            <div class="break-words w-3/12 self-center">
                                Дата сдачі
                            </div>
                            <div class="break-words w-3/12 sm:w-2/12 self-center">
                                Дата публікації
                            </div>
                        </div>
                        @foreach($homeworks as $homework)
                            <div class="flex flex-row">
                                <div class="break-words w-6/12 sm:w-7/12 self-center">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($homework->description, ['p']). 100) !!}
                                </div>
                                <div class="break-words w-3/12 text-center self-center">
                                    {{ $homework->ending_at }}
                                </div>
                                <div class="break-words w-3/12 sm:w-2/12 text-center self-center">
                                    {{ $homework->created_at }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div data-menu-section="grades" class="px-2 sm:px-12 py-6 w-full space-y-4">
                @if($grades->count() === 0)
                    <div class="font-bold text-center italic text-xl">
                        Ваших оцінок нема ще.
                    </div>
                @else
                    <div class="font-bold text-center text-xl">
                        Ваші оцінки
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-row text-center font-bold text-lg">
                            <div class="w-6/12 sm:w-7/12 self-center break-words">
                                Заняття
                            </div>
                            <div class="w-3/12 self-center break-words">
                                Присутність
                            </div>
                            <div class="w-3/12 sm:w-2/12 self-center break-words">
                                Оцінка
                            </div>
                        </div>
                        @foreach($grades as $grade)
                            <div class="flex flex-row">
                                <div class="w-6/12 sm:w-7/12 self-center break-words">
                                    {!! \Illuminate\Support\Str::limit(
                                        strip_tags($grade->lesson->description, ['p']). 100) !!}
                                </div>
                                <div class="w-3/12 text-center self-center break-words">
                                    {{ $grade->is_present ? 'Присутній' : 'Відсутній' }}
                                </div>
                                <div class="w-3/12 sm:w-2/12 text-center self-center break-words">
                                    {{ $grade->grade }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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
