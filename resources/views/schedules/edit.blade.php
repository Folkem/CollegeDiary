@extends('layouts.app')

@section('title')
    Редагування розкладу занять групи {{ $group->humanName }}
@endsection

@section('body')
    @include('layouts.header')

    <!-- 640px and above warning -->
    <div class="block sm:hidden flex flex-col w-full mt-32">
        <div class="m-auto font-gotham-pro text-red-500 text-center text-2xl sm:text-3xl md:text-4xl px-8">
            Через насичений інтерфейс, адмін-панель доступна лише при розширенні екрану <b>640 px</b>
            (пікселя) і вище (іншими словами: лише з комп'ютерів та деяких планшетів).
            <br><br>
            Вибачте за незручності.
        </div>
    </div>

    <main class="max-w-1200 hidden sm:flex flex-col gap-6 mx-auto pt-20 text-blue-900">
        <div class="text-2xl md:text-3xl font-gotham-pro-bold mx-auto px-8 text-center">
            Редагування розкладу занять групи {{ $group->humanName }}
        </div>
        @if(session()->has('message'))
            <div class="text-xl text-center">
                {{ session('message') }}
            </div>
        @endif
        <div class="w-11/12 md:w-10/12 lg:w-9/12 xl:w-8/12 mx-auto flex flex-col gap-4">
            <div class="flex flex-col gap-2">
                <div class="flex flex-col w-full text-center font-museo-cyrl p-4 gap-2">
                    <div class="flex flex-row w-full font-bold border-solid border border-black rounded-t-xl py-2">
                        <div class="w-2/12">День</div>
                        <div class="w-1/12">№</div>
                        <div class="w-6/12">Дисципліна</div>
                        <div class="w-3/12">Варіант</div>
                    </div>
                    @foreach($group->fullLessonSchedule as $weekDay => $daySchedule)
                        <div class="flex flex-row pb-2 border-solid border-b border-black">
                            <div class="w-3/12 sm:w-2/12 self-center">{{ ucfirst(__($weekDay)) }}</div>
                            <div class="w-9/12 sm:w-10/12 flex flex-col">
                                @foreach($daySchedule as $number => $numberSchedule)
                                    <div class="flex flex-row border-b border-solid border-black py-2
                                                    @if(array_key_last($daySchedule) == $number) border-none @endif">
                                        <div class="w-1/10 self-center">
                                            {{ $number }}
                                        </div>
                                        <div class="w-9/10 flex flex-row">
                                            <div class="w-2/3 flex flex-col" data-parent-number="{{ $number }}">
                                                <!--suppress HtmlFormInputWithoutLabel -->
                                                <select data-number="{{ $number }}" data-weekday="{{ $weekDay }}"
                                                        data-variant="постійно"
                                                        class="break-words w-full appearance-none px-2 py-1 text-base
                                                            border-solid border border-blue-900 bg-white rounded-md
                                                            @if(!array_key_exists('постійно', $numberSchedule)) hidden @endif">
                                                    <option></option>
                                                    @foreach($disciplines as $discipline)
                                                        <option value="{{ $discipline->id }}"
                                                                @if(($numberSchedule['постійно'] ?? null) == $discipline->forStudent)
                                                                selected @endif>
                                                            {{ $discipline->forStudent }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <!--suppress HtmlFormInputWithoutLabel -->
                                                <select data-number="{{ $number }}" data-weekday="{{ $weekDay }}"
                                                        data-variant="чисельник"
                                                        class="break-words w-full appearance-none px-2 py-1 text-base
                                                        border-solid border border-blue-900 bg-white rounded-md
                                                        @if(array_key_exists('постійно', $numberSchedule)) hidden @endif">
                                                    <option></option>
                                                    @foreach($disciplines as $discipline)
                                                        <option value="{{ $discipline->id }}"
                                                                @if(($numberSchedule['чисельник'] ?? null) == $discipline->forStudent)
                                                                selected @endif>
                                                            {{ $discipline->forStudent }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <!--suppress HtmlFormInputWithoutLabel -->
                                                <select data-number="{{ $number }}" data-weekday="{{ $weekDay }}"
                                                        data-variant="знаменник"
                                                        class="break-words w-full appearance-none px-2 py-1 text-base
                                                        border-solid border border-blue-900 bg-white rounded-md
                                                        @if(array_key_exists('постійно', $numberSchedule)) hidden @endif">
                                                    <option></option>
                                                    @foreach($disciplines as $discipline)
                                                        <option value="{{ $discipline->id }}"
                                                                @if(($numberSchedule['знаменник'] ?? null) == $discipline->forStudent)
                                                                selected @endif>
                                                            {{ $discipline->forStudent }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="w-1/3 self-center">
                                                <!--suppress HtmlFormInputWithoutLabel -->
                                                <select oninput="updateLessonNumber({{ $number }}, '{{ $weekDay }}', this)"
                                                    class="break-words w-full appearance-none px-2 py-1 text-base
                                                        border-solid border border-blue-900 bg-white rounded-md">
                                                    <option value="постійно"
                                                            @if(array_key_exists('постійно', $numberSchedule)) selected @endif>
                                                        постійно
                                                    </option>
                                                    <option value="по варіанту"
                                                            @if(!array_key_exists('постійно', $numberSchedule)) selected @endif>
                                                        по варіанту
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <button onclick="updateLessonSchedule()"
                        class="w-full bg-green-500 hover:bg-green-600 p-2 text-white text-lg font-bold">
                    ЗБЕРЕГТИ
                </button>
            </div>
        </div>
    </main>

    @include('layouts.footer')
@endsection

@push('scripts')
    <script>
        function updateLessonNumber(number, weekDay, variantSelect) {
            const numberFirst = document.querySelector(
                `[data-number="${number}"][data-variant="постійно"][data-weekday="${weekDay}"]`);
            const numberSecond = document.querySelector(
                `[data-number="${number}"][data-variant="чисельник"][data-weekday="${weekDay}"]`);
            const numberThird = document.querySelector(
                `[data-number="${number}"][data-variant="знаменник"][data-weekday="${weekDay}"]`);

            if (variantSelect.value === 'постійно') {
                numberFirst.classList.toggle('hidden', false);
                numberSecond.classList.toggle('hidden', true);
                numberThird.classList.toggle('hidden', true);
            } else {
                numberFirst.classList.toggle('hidden', true);
                numberSecond.classList.toggle('hidden', false);
                numberThird.classList.toggle('hidden', false);
            }
        }

        const WEEK_DAYS = {
            'monday': 1,
            'tuesday': 2,
            'wednesday': 3,
            'thursday': 4,
            'friday': 5,
        };

        function updateLessonSchedule() {
            const lessonSchedule = {
                1: {},
                2: {},
                3: {},
                4: {},
                5: {},
            };

            const lessonScheduleItems = document.querySelectorAll('[data-number][data-variant][data-weekday]');

            for (const item of lessonScheduleItems) {
                if (item.classList.contains('hidden')) continue;
                if (item.value === '') continue;

                const itemWeekDay = item.getAttribute('data-weekday');
                const itemNumber = item.getAttribute('data-number');
                const itemVariant = item.getAttribute('data-variant');

                if ((typeof lessonSchedule[WEEK_DAYS[itemWeekDay]][itemNumber]) !== 'object') {
                    lessonSchedule[WEEK_DAYS[itemWeekDay]][itemNumber] = {};
                }

                lessonSchedule[WEEK_DAYS[itemWeekDay]][itemNumber][itemVariant] = item.value;
            }

            try {
                fetch('{{ route('schedules.lessons.update', $group) }}', {
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        '_method': 'put',
                        'schedule': JSON.stringify(lessonSchedule),
                    }),
                }).then(result => {
                    if (['200', '422'].includes(`${result.status}`)) {
                        return result.json();
                    } else {
                        throw new Error();
                    }
                }).then(json => {
                    if (json['success']) {
                        location.reload();
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
                    console.log('Помилка оновлення розкладу');
                    console.error(e);
                    alert('Серверна помилка');
                });
            } catch (e) {
                console.error(e);
            }

            return false;
        }
    </script>
@endpush
