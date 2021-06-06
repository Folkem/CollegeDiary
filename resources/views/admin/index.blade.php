@extends('layouts.app')

@section('title', 'Адмін-панель')

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

    <!-- Main menu -->
    <main class="max-w-1200 hidden lg:flex flex-col lg:flex-row mx-auto">
        <div class="font-museo-cyrl md:w-5/12 bg-blue-500 flex flex-col text-base sm:text-lg md:text-xl lg:text-3xl">
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white bg-blue-700 text-center">
                Панель адміністратора
            </div>
            <hr>
            @if(auth()->user()->role->name === 'admin')
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white cursor-pointer"
                 data-menu-button="students">
                Студенти
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white cursor-pointer"
                 data-menu-button="teachers">
                Викладачі
            </div>
            <hr>
            @endif
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
        <div class="bg-blue-100 md:w-7/12 flex text-blue-800 font-gotham-pro">
            @if(auth()->user()->role->name === 'admin')
            <div data-menu-section="students" class="px-2 sm:px-12 py-6 w-full space-y-4">
                <div class="text-3xl font-bold">
                    Таблиця студентів
                </div>
                <a class="underline text-base" href="{{ route('students.create') }}">
                    Додати новий запис
                </a>
                <div class="text-center">
                    @if($students->count() === 0)
                        <div class="italic font-bold text-xl">
                            Студентів нема.
                        </div>
                    @else
                        <div class="rounded-lg break-words bg-white border-2 border-solid border-blue-700 p-2
                            flex flex-col gap-4">
                            <div class="flex flex-row font-bold text-lg border-b-2 border-solid border-blue-700">
                                <div class="w-4/12">П.І.Б.</div>
                                <div class="w-4/12">Пошта</div>
                                <div class="w-2/12">Група</div>
                                <div class="w-2/12 text-sm self-center">Кнопки</div>
                            </div>
                            @foreach($students as $student)
                                <div class="flex flex-row text-sm">
                                    <div class="w-4/12">{{ $student->name }}</div>
                                    <div class="w-4/12">{{ $student->email }}</div>
                                    <div class="w-2/12">{{ $student->group->human_name }}</div>
                                    <div class="w-2/12 flex flex-row justify-evenly">
                                        <div>
                                            <a href="{{ route('students.edit', $student) }}">
                                                <i class="fas fa-solid fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('students.destroy', $student) }}"
                                                  method="post"
                                                  onsubmit="return deleteStudent('{{ $student->name }}');">
                                                @csrf
                                                @method('delete')
                                                <button type="submit">
                                                    <i class="fas fa-solid fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div data-menu-section="teachers" class="px-2 sm:px-12 py-6 hidden w-full">
                <div class="text-3xl font-bold">
                    Таблиця викладачів
                </div>
                <a class="underline text-base" href="{{ route('teachers.create') }}">
                    Додати новий запис
                </a>
                <div class="text-center">
                    @if($teachers->count() === 0)
                        <div class="italic font-bold text-xl">
                            Викладачів нема.
                        </div>
                    @else
                        <div class="rounded-lg break-words bg-white border-2 border-solid border-blue-700 p-2
                            flex flex-col gap-4">
                            <div class="flex flex-row font-bold text-lg border-b-2 border-solid border-blue-700">
                                <div class="w-5/12">П.І.Б.</div>
                                <div class="w-5/12">Пошта</div>
                                <div class="w-2/12 text-sm self-center">Кнопки</div>
                            </div>
                            @foreach($teachers as $teacher)
                                <div class="flex flex-row text-sm">
                                    <div class="w-5/12">{{ $teacher->name }}</div>
                                    <div class="w-5/12">{{ $teacher->email }}</div>
                                    <div class="w-2/12 flex flex-row justify-evenly">
                                        <div>
                                            <a href="{{ route('teachers.edit', $teacher) }}">
                                                <i class="fas fa-solid fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('teachers.destroy', $teacher) }}"
                                                  method="post"
                                                  onsubmit="return deleteTeacher('{{ $teacher->name }}');">
                                                @csrf
                                                @method('delete')
                                                <button type="submit">
                                                    <i class="fas fa-solid fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif
            <div data-menu-section="schedules" class="px-2 sm:px-12 py-6 hidden w-full">
                Розклади
            </div>
            <div data-menu-section="disciplines" class="px-2 sm:px-12 py-6 hidden w-full">
                <div class="text-3xl font-bold">
                    Таблиця дисциплін
                </div>
                <a class="underline text-base" href="{{ route('disciplines.create') }}">
                    Додати новий запис
                </a>
                <div class="text-center">
                    @if($disciplines->count() === 0)
                        <div class="italic font-bold text-xl">
                            Дисциплін нема.
                        </div>
                    @else
                        <div class="rounded-lg break-words bg-white border-2 border-solid border-blue-700 p-2
                            flex flex-col gap-4">
                            <div class="flex flex-row font-bold text-lg border-b-2 border-solid border-blue-700">
                                <div class="w-4/12">Предмет</div>
                                <div class="w-4/12">Викладач</div>
                                <div class="w-2/12">Група</div>
                                <div class="w-2/12 text-sm self-center">Кнопки</div>
                            </div>
                            @foreach($disciplines as $discipline)
                                <div class="flex flex-row text-sm">
                                    <div class="w-4/12">{{ $discipline->subject }}</div>
                                    <div class="w-4/12">{{ $discipline->teacher->name }}</div>
                                    <div class="w-2/12">{{ $discipline->group->humanName }}</div>
                                    <div class="w-2/12 flex flex-row justify-evenly">
                                        <div>
                                            <a href="{{ route('disciplines.edit', $discipline) }}">
                                                <i class="fas fa-solid fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('disciplines.destroy', $discipline) }}"
                                                  method="post"
                                                  onsubmit="return deleteDiscipline('{{ $discipline->forAdmin }}');">
                                                @csrf
                                                @method('delete')
                                                <button type="submit">
                                                    <i class="fas fa-solid fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
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
        @if(auth()->user()->role->name === 'admin')

        function deleteStudent(studentName) {
            try {
                return confirm(`Видалити студента ${studentName}?`);
            } catch (e) {
                console.error(e);
            }

            return false;
        }

        function deleteTeacher(teacherName) {
            try {
                return confirm(`Видалити викладача ${(teacherName)}?`);
            } catch (e) {
                console.error(e);
            }

            return false;
        }

        @endif

        function deleteDiscipline(disciplineName) {
            try {
                return confirm(`Видалити дисципліну ${disciplineName}?`);
            } catch (e) {
                console.error(e);
            }

            return false;
        }

        // todo: delete for each other model
    </script>
    <!-- Session message -->
    <script>
        @if(session()->has('message'))
            alert('{{ session('message') }}');
        @endif
    </script>
@endpush
