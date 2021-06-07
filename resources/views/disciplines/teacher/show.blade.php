@extends('layouts.app')

@section('title')
    Дисципліна {{ $discipline->forTeacher }}
@endsection

@section('body')
    @include('layouts.header')

    <main class="max-w-1200 flex flex-col lg:flex-row mx-auto">
        <div class="font-museo-cyrl lg:w-5/12 bg-blue-500 flex flex-col text-base sm:text-lg md:text-xl lg:text-3xl">
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
        <div class="bg-blue-100 lg:w-7/12 flex text-blue-800 font-gotham-pro">
            <div data-menu-section="lessons" class="px-2 sm:px-12 py-6 w-full space-y-4 space-y-4">
                <a class="underline text-base" href="{{ route('lessons.create', $discipline) }}">
                    Додати нове заняття
                </a>
                <div class="text-center">
                    @if($lessons->count() === 0)
                        <div class="italic font-bold text-xl">
                            Занять нема.
                        </div>
                    @else
                        <div class="rounded-lg break-words bg-white border-2 border-solid border-blue-700 p-2
                            flex flex-col gap-4">
                            <div class="flex flex-row font-bold text-lg border-b-2 border-solid border-blue-700">
                                <div class="w-5/12">Опис</div>
                                <div class="w-4/12">Тип заняття</div>
                                <div class="w-3/12 text-sm self-center">Кнопки</div>
                            </div>
                            @foreach($lessons as $lesson)
                                <div class="flex flex-row text-sm">
                                    <div class="w-5/12">
                                        {!! \Illuminate\Support\Str::limit(strip_tags($lesson->description), 50) !!}
                                    </div>
                                    <div class="w-4/12">{{ $lesson->lessonType->name }}</div>
                                    <div class="w-3/12 flex flex-row justify-evenly">
                                        <div>
                                            <a href="{{ route('lessons.show', $lesson) }}">
                                                <i class="fas fa-solid fa-book-open"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{ route('lessons.edit', $lesson) }}">
                                                <i class="fas fa-solid fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('lessons.destroy', $lesson) }}"
                                                  method="post"
                                                  onsubmit="return deleteLesson(
                                                      '{!! \Illuminate\Support\Str::limit(strip_tags($lesson->description)) !!}'
                                                      );">
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
            <div data-menu-section="homeworks" class="px-2 sm:px-12 py-6 w-full space-y-4">
                <a class="underline text-base" href="{{ route('homeworks.create', $discipline) }}">
                    Додати нове домашнє завдання
                </a>
                <div class="text-center">
                    @if($homeworks->count() === 0)
                        <div class="italic font-bold text-xl">
                            Домашніх завдань нема.
                        </div>
                    @else
                        <div class="rounded-lg break-words bg-white border-2 border-solid border-blue-700 p-2
                            flex flex-col gap-4">
                            <div class="flex flex-row font-bold text-lg border-b-2 border-solid border-blue-700">
                                <div class="w-5/12">Опис</div>
                                <div class="w-4/12">Дата сдачі</div>
                                <div class="w-3/12 text-sm self-center">Кнопки</div>
                            </div>
                            @foreach($homeworks as $homework)
                                <div class="flex flex-row text-sm">
                                    <div class="w-5/12">
                                        {!! \Illuminate\Support\Str::limit(strip_tags($homework->description), 50) !!}
                                    </div>
                                    <div class="w-4/12">{{ $homework->ending_at }}</div>
                                    <div class="w-3/12 flex flex-row justify-evenly">
                                        <div>
                                            <a href="{{ route('homeworks.show', $homework) }}">
                                                <i class="fas fa-solid fa-book-open"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{ route('homeworks.edit', $homework) }}">
                                                <i class="fas fa-solid fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('homeworks.destroy', $homework) }}"
                                                  method="post"
                                                  onsubmit="return deleteHomework(
                                                      '{!! \Illuminate\Support\Str::limit(strip_tags($homework->description)) !!}'
                                                      );">
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
            <div data-menu-section="grades" class="px-2 sm:px-12 py-6 w-full space-y-4">
                @if(count($grades) === 0)
                    <div class="font-bold text-center italic text-xl">
                        Оцінок нема ще.
                    </div>
                @else
                    <div class="font-bold text-center text-xl">
                        Оцінки
                    </div>
                    <div class="flex flex-col bg-white rounded-2xl overflow-y-hidden overflow-x-scroll p-4
                        border-2 border-solid border-blue-900">
                        <div class="flex flex-row min-w-min">
                            <div class="w-32 text-center self-center">П.І.Б.</div>
                            @foreach($lessons as $lesson)
                                <div class="w-32 text-center self-center">
                                    {{ $lesson->created_at }}
                                </div>
                            @endforeach
                        </div>
                        @foreach($grades as $studentId => $studentGrades)
                            <div class="flex flex-row border-t border-solid border-blue-900 min-w-min">
                                <div class="w-32 border-r border-solid border-blue-900 py-2 px-1">
                                    {{ $students->find($studentId)->name }}
                                </div>
                                @foreach($studentGrades as $grade)
                                    <div class="w-32 text-center self-center flex flex-row gap-1 px-1">
                                        <!--suppress HtmlFormInputWithoutLabel -->
                                        <select data-student-id="{{ $studentId }}" data-grade-id="{{ $grade['id'] ?? null }}"
                                                data-lesson-id="{{ $grade['lesson_id'] }}"
                                                class="bg-white border border-solid border-blue-900 rounded-md">
                                            <option value="-1"></option>
                                            <option value="present"
                                            @if($grade['is_present'] === true) selected @endif>
                                                Присутній
                                            </option>
                                            <option value="absent"
                                            @if($grade['is_present'] === false) selected @endif>
                                                Відсутній
                                            </option>
                                            @for($i = 0; $i <= 100; $i++)
                                                <option value="{{ $i }}"
                                                @if($grade['grade'] === $i) selected @endif>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        <i class="fas fa-solid text-xl fa-check cursor-pointer"
                                            onclick="updateGrade(this.parentNode.children[0]);"></i>
                                    </div>
                                @endforeach
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

        function updateGrade(selectElement) {
            try {
                const studentId = selectElement.getAttribute('data-student-id');
                const lessonId = selectElement.getAttribute('data-lesson-id');
                const gradeId = selectElement.getAttribute('data-grade-id');
                const value = selectElement.value;

                fetch(`{{ route('grade.update-create') }}`, {
                    method: 'put',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        'student-id': parseInt(studentId),
                        'lesson-id': parseInt(lessonId),
                        'grade-id': gradeId,
                        'value': value,
                    }),
                }).then(result => {
                    if (['200', '422'].includes(`${result.status}`)) {
                        return result.json();
                    } else {
                        throw new Error();
                    }
                }).then(json => {
                    if (json['success']) {
                        alert('Оновлено');
                    } else {
                        const errorBag = json['errors'];
                        let message = json['message'];
                        for (const errorBlock in errorBag) {
                            for (const errorMessage of errorBag[errorBlock]) {
                                message += ` ${errorMessage}`;
                            }
                        }
                        console.log(message);
                        alert(message);
                    }
                }).catch(e => {
                    console.log('Помилка оновлення оцынки');
                    console.error(e);
                    alert('Серверна помилка');
                });
            } catch (e) {
                console.error(e);
                alert('Серверна помилка');
            }
        }
    </script>
    <!-- Session message -->
    <script>
        @if(session()->has('message'))
        alert('{{ session('message') }}');
        @endif
    </script>
@endpush
