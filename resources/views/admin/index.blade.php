@extends('layouts.app')

@section('title', 'Адмін-панель')

@section('body')
    @include('layouts.header')

    <!-- Main menu -->
    <main class="max-w-1200 flex flex-col lg:flex-row mx-auto">
        <div class="font-museo-cyrl lg:w-5/12 bg-blue-500 flex flex-col text-base sm:text-lg md:text-xl lg:text-3xl">
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
                 data-menu-button="groups">
                Групи
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
        <div class="bg-blue-100 lg:w-7/12 flex text-blue-800 font-gotham-pro">
            @if(auth()->user()->role->name === 'admin')
            <div data-menu-section="students" class="px-2 sm:px-12 py-6 w-full space-y-4">
                <div class="text-3xl font-bold">
                    Таблиця студентів
                </div>
                <div>
                    <a class="underline text-base" href="{{ route('students.create') }}">
                        Додати новий запис
                    </a>
                </div>
                <div>
                    <a class="underline text-base" href="{{ route('upload.students.show') }}">
                        Завантажити з Excel
                    </a>
                </div>
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
                                    <div class="w-2/12">{{ $student->group ? $student->group->human_name : '' }}</div>
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
            <div data-menu-section="teachers" class="px-2 sm:px-12 py-6 hidden w-full space-y-4">
                <div class="text-3xl font-bold">
                    Таблиця викладачів
                </div>
                <div>
                    <a class="underline text-base" href="{{ route('teachers.create') }}">
                        Додати новий запис
                    </a>
                </div>
                <div>
                    <a class="underline text-base" href="{{ route('upload.teachers.show') }}">
                        Завантажити з Excel
                    </a>
                </div>
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
            <div data-menu-section="groups" class="px-2 sm:px-12 py-6 hidden w-full space-y-4">
                <div class="text-3xl font-bold">
                    Таблиця груп
                </div>
                <div>
                    <a class="underline text-base" href="{{ route('groups.create') }}">
                        Додати новий запис
                    </a>
                </div>
                <div class="text-center">
                    @if($groups->count() === 0)
                        <div class="italic font-bold text-xl">
                            Груп нема.
                        </div>
                    @else
                        <div class="rounded-lg break-words bg-white border-2 border-solid border-blue-700 p-2
                            flex flex-col gap-4">
                            <div class="flex flex-row font-bold text-lg border-b-2 border-solid border-blue-700">
                                <div class="w-8/12">Назва групи</div>
                                <div class="w-4/12 text-sm self-center">Кнопки</div>
                            </div>
                            @foreach($groups as $group)
                                <div class="flex flex-row text-sm">
                                    <div class="w-8/12">{{ $group->human_name }}</div>
                                    <div class="w-4/12 flex flex-row justify-evenly">
                                        <div>
                                            <a href="{{ route('schedules.lessons.edit', $group) }}"
                                               title="Редагувати розклад">
                                                <i class="fas fa-solid fa-calendar-alt"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{ route('groups.edit', $group) }}">
                                                <i class="fas fa-solid fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('groups.destroy', $group) }}"
                                                  method="post"
                                                  onsubmit="return deleteGroup('{{ $group->humanName }}');">
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
            <div data-menu-section="disciplines" class="px-2 sm:px-12 py-6 hidden w-full space-y-4">
                <div class="text-3xl font-bold">
                    Таблиця дисциплін
                </div>
                <div>
                    <a class="underline text-base" href="{{ route('disciplines.create') }}">
                        Додати новий запис
                    </a>
                </div>
                <div>
                    <a class="underline text-base" href="{{ route('upload.disciplines.show') }}">
                        Завантажити з Excel
                    </a>
                </div>
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
            <div data-menu-section="news" class="px-2 sm:px-12 py-6 hidden w-full space-y-4">
                <div class="text-3xl font-bold">
                    Таблиця новин
                </div>
                <div>
                    <a class="underline text-base" href="{{ route('news.create') }}">
                        Додати новий запис
                    </a>
                </div>
                <div class="text-center">
                    @if($newsList->count() === 0)
                        <div class="italic font-bold text-xl">
                            Новин нема.
                        </div>
                    @else
                        <div class="rounded-lg break-words bg-white border-2 border-solid border-blue-700 p-2
                            flex flex-col gap-4">
                            <div class="flex flex-row font-bold text-lg border-b-2 border-solid border-blue-700">
                                <div class="w-7/12">Заголовок</div>
                                <div class="w-3/12">Дата створення</div>
                                <div class="w-2/12 text-sm self-center">Кнопки</div>
                            </div>
                            @foreach($newsList as $news)
                                <div class="flex flex-row text-sm">
                                    <div class="w-7/12">{{ $news->title }}</div>
                                    <div class="w-3/12">{{ $news->created_at }}</div>
                                    <div class="w-2/12 flex flex-row justify-evenly">
                                        <div>
                                            <a href="{{ route('news.edit', $news) }}">
                                                <i class="fas fa-solid fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('news.destroy', $news) }}"
                                                  method="post"
                                                  onsubmit="return deleteNews('{{ $news->title }}');">
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

        function deleteDiscipline(discipline) {
            try {
                return confirm(`Видалити дисципліну ${discipline}?`);
            } catch (e) {
                console.error(e);
            }

            return false;
        }

        function deleteGroup(group) {
            try {
                return confirm(`Видалити групу ${group}?`);
            } catch (e) {
                console.error(e);
            }

            return false;
        }

        function deleteNews(newsTitle) {
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
