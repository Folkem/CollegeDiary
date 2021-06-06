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
        <div class="bg-blue-100 md:w-7/12 flex text-blue-800 font-gotham-pro">
            <div data-menu-section="students" class="px-2 sm:px-12 py-6 w-full space-y-4">
                <div class="text-3xl font-bold">
                    Таблиця студентів
                </div>
                <div class="underline text-base" data-target="student-add-new">
                    Додати новий запис
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
                                    <div class="w-2/12">{{ $student->group->human_name }}</div>
                                    <div class="w-2/12 flex flex-row justify-evenly">
                                        <div>
                                            <button data-target="student-edit">
                                                <i class="fas fa-solid fa-pencil-alt"></i>
                                            </button>
                                        </div>
                                        <div>
                                            <form action="{{ route('students.destroy', $student) }}"
                                                  method="post" onsubmit="return deleteStudent('{{ $student->name }}');">
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

    <div>
        <div class="absolute w-screen h-screen z-30 bg-blue-300 bg-opacity-75 flex top-0 hidden"
             data-menu="student-add-new">
            <div class="self-center bg-white rounded-lg mx-auto p-4">
                Додавання студента
            </div>
        </div>
        <div class="absolute w-screen h-screen z-30 bg-blue-300 bg-opacity-75 flex top-0 hidden"
             data-menu="student-edit">
            <div class="self-center bg-white rounded-lg mx-auto p-4">
                Редагування студента
            </div>
        </div>
        <div class="absolute w-screen h-screen z-30 bg-blue-300 bg-opacity-75 flex top-0 hidden"
             data-menu="teacher-add-new">
            <div class="self-center bg-white rounded-lg mx-auto p-4">
                Додавання викладача
            </div>
        </div>
        <div class="absolute w-screen h-screen z-30 bg-blue-300 bg-opacity-75 flex top-0 hidden"
             data-menu="teacher-edit">
            <div class="self-center bg-white rounded-lg mx-auto p-4">
                Редагування викладача
            </div>
        </div>
        <div class="absolute w-screen h-screen z-30 bg-blue-300 bg-opacity-75 flex top-0 hidden"
             data-menu="discipline-add-new">
            <div class="self-center bg-white rounded-lg mx-auto p-4">
                Додавання дисципліни
            </div>
        </div>
        <div class="absolute w-screen h-screen z-30 bg-blue-300 bg-opacity-75 flex top-0 hidden"
             data-menu="discipline-edit">
            <div class="self-center bg-white rounded-lg mx-auto p-4">
                Редагування дисципліни
            </div>
        </div>
        <div class="absolute w-screen h-screen z-30 bg-blue-300 bg-opacity-75 flex top-0 hidden"
             data-menu="news-add-new">
            <div class="self-center bg-white rounded-lg mx-auto p-4">
                Додавання новини
            </div>
        </div>
        <div class="absolute w-screen h-screen z-30 bg-blue-300 bg-opacity-75 flex top-0 hidden"
             data-menu="news-edit">
            <div class="self-center bg-white rounded-lg mx-auto p-4">
                Редагування новини
            </div>
        </div>
    </div>

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
        const targetButtons = Array.from(document.querySelectorAll('[data-target]'));
        const targetMenus = Array.from(document.querySelectorAll('[data-menu]'));

        targetButtons.forEach(button => {
            const menu = targetMenus.find(value =>
                value.getAttribute('data-menu') === button.getAttribute('data-target'));
            button.addEventListener('click', () => {
                menu.classList.toggle('hidden', false);
            });
        });
    </script>
    <script>
        function deleteStudent(studentName) {
            try {
                return confirm(`Видалити студента ${studentName}?`);
            } catch (e) {
                console.error(e);
            }

            return false;
        }
    </script>
@endpush
