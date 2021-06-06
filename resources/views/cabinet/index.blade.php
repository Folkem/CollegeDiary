@extends('layouts.app')

@section('title', 'Особистий кабінет')

@section('body')
    @include('layouts.header')

    <div class="max-w-1200 flex flex-col md:flex-row mx-auto">
        <div class="font-museo-cyrl md:w-5/12 bg-blue-500 flex flex-col text-base sm:text-lg
        md:text-xl lg:text-3xl">
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white">
                Особистий кабінет
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white bg-white text-blue-900 cursor-pointer"
                 data-menu-button="profile">
                Ваш профіль
            </div>
            <hr>
            @if(in_array(auth()->user()->role->name, ['teacher', 'student']))
                <div class="text-white px-8 py-4 border-solid border-b-2 border-white cursor-pointer"
                     data-menu-button="lesson-schedule">
                    Розклад
                </div>
            @endif
            {{--            <div class="text-white px-8 py-4 md:border-solid border-b-2 border-white cursor-pointer"--}}
            {{--                 data-menu-button="notification-settings">--}}
            {{--                Налаштування повідомлень--}}
            {{--            </div>--}}
        </div>
        <div class="bg-blue-100 md:w-7/12 flex">
            <div data-menu-section="profile" class="px-2 sm:px-12 py-6">
                <div class="flex flex-col gap-2 mb-8">
                    <div class="font-gotham-pro-bold text-blue-900 text-3xl">
                        Ім'я
                    </div>
                    <div class="text-xl">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="font-gotham-pro-bold text-blue-900 text-3xl">
                        Пошта
                    </div>
                    <div class="text-xl">
                        {{ auth()->user()->email }}
                    </div>
                    <div class="font-gotham-pro-bold text-blue-900 text-3xl">
                        Роль
                    </div>
                    <div class="text-xl">
                        {{ \Illuminate\Support\Str::ucfirst(__(auth()->user()->role->name)) }}
                        @if(auth()->user()->role->name === 'student')
                            {{ auth()->user()->group->human_name }}
                        @endif
                    </div>
                </div>
                <div class="font-gotham-pro-bold text-blue-900 text-3xl mb-8">
                    Зміна паролю
                </div>
                @if(session()->has('message'))
                    <div class="text-xl font-gotham-pro-bold">
                        {{ session('message') }}
                    </div>
                @endif
                <form action="{{ route('cabinet.password.update') }}" method="post"
                      class="flex flex-col gap-2" autocomplete="on">
                    @csrf
                    @method('put')
                    <div class="flex flex-col gap-2">
                        <label for="old_password" class="font-museo-cyrl text-indigo-700 text-base">
                            Введіть попередній пароль
                        </label>
                        <input type="password" id="old_password" name="old_password" required
                               autocomplete="current-password"
                               class="px-2 rounded-full border-solid border-2 border-blue-900 text-base">
                    </div>
                    @error('old_password')
                    <div class="text-red-500 italic font-gotham-pro-bold text-base">
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="flex flex-col gap-2">
                        <label for="password" class="font-museo-cyrl text-indigo-700 text-base">
                            Введіть новий пароль
                        </label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                               class="px-2 rounded-full border-solid border-2 border-blue-900 text-base">
                    </div>
                    @error('password')
                    <div class="text-red-500 italic font-gotham-pro-bold text-base">
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="flex flex-col gap-2">
                        <label for="password_confirmation" class="font-museo-cyrl text-indigo-700 text-base">
                            Підтвердіть новий пароль
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               autocomplete="new-password"
                               class="px-2 rounded-full border-solid border-2 border-blue-900 text-base">
                    </div>
                    @error('password_confirmation')
                    <div class="text-red-500 italic font-gotham-pro-bold text-base">
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="mt-4">
                        <button type="submit" class="font-museo-cyrl text-xl bg-green-500
                            hover:bg-green-600 px-8 py-1 text-white rounded-full">
                            Зберегти
                        </button>
                    </div>
                </form>
                <div class="font-gotham-pro-bold text-blue-900 text-3xl mb-8 mt-12">
                    Зміна аватару
                </div>
                @if(session()->has('message'))
                    <div class="text-xl font-gotham-pro-bold">
                        {{ session('message') }}
                    </div>
                @endif
                <form action="{{ route('cabinet.avatar.update') }}" method="post"
                      class="flex flex-col gap-2" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="flex flex-col gap-2">
                        <label for="avatar" class="font-museo-cyrl font-bold border border-solid border-indigo-700
                            rounded-full bg-white text-center px-3 py-1 text-indigo-700 text-base">
                            Виберіть зображення
                        </label>
                        <input type="file" id="avatar" name="avatar" required accept="image/*"
                               class="hidden">
                    </div>
                    @error('avatar')
                    <div class="text-red-500 italic font-gotham-pro-bold text-base">
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="mt-4">
                        <button type="submit" class="font-museo-cyrl text-xl bg-green-500
                            hover:bg-green-600 px-8 py-1 text-white rounded-full">
                            Зберегти
                        </button>
                    </div>
                </form>
            </div>
            @if(in_array(auth()->user()->role->name, ['teacher', 'student']))
                <div data-menu-section="lesson-schedule" class="w-full hidden">
                    @if(auth()->user()->role->name === 'student')
                        @if(auth()->user()->group->lessonScheduleItems()->count() === 0)
                            <div>Розкладу ще нема.</div>
                        @else
                            <div class="flex flex-col w-full text-center font-museo-cyrl p-4 gap-2">
                                <div
                                    class="flex flex-row w-full font-bold border-solid border border-black rounded-t-xl py-2">
                                    <div class="w-3/12 sm:w-2/12">День</div>
                                    <div class="w-1/12">№</div>
                                    <div class="w-8/12 sm:w-9/12">Дисципліна</div>
                                </div>
                                @foreach(auth()->user()->group->lessonSchedule as $weekDay => $daySchedule)
                                    <div class="flex flex-row pb-2 border-solid border-b border-black">
                                        <div class="w-3/12 sm:w-2/12 self-center">{{ $weekDay }}</div>
                                        <div class="w-9/12 sm:w-10/12 flex flex-col">
                                            @foreach($daySchedule as $number => $numberSchedule)
                                                <div class="flex flex-row border-b border-solid border-black
                                                    @if(last($daySchedule) == $numberSchedule) border-none @endif">
                                                    <div class="w-1/10 self-center">
                                                        {{ $number }}
                                                    </div>
                                                    <div class="w-9/10">
                                                        @if(array_key_exists('постійно', $numberSchedule))
                                                            {{ $numberSchedule['постійно'] }}
                                                        @else
                                                             {!! $numberSchedule['чисельник'] ?? '<b>Відсутньо</b>' !!}  /
                                                             {!! $numberSchedule['знаменник'] ?? '<b>Відсутньо</b>' !!}
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        @if(count(auth()->user()->teacherSchedule) === 0)
                            <div>Розкладу ще нема.</div>
                        @else
                            <div class="flex flex-col w-full text-center font-museo-cyrl p-4">
                                <div class="flex flex-row w-full font-bold border-solid border border-black rounded-t-xl py-2">
                                    <div class="w-3/12 sm:w-2/12">День</div>
                                    <div class="w-1/12">№</div>
                                    <div class="w-8/12 sm:w-9/12">Дисципліна</div>
                                </div>
                                @foreach(auth()->user()->teacherSchedule as $weekDay => $daySchedule)
                                    <div class="flex flex-row border-solid border-b border-black">
                                        <div class="w-3/12 sm:w-2/12 self-center">{{ $weekDay }}</div>
                                        <div class="w-9/12 sm:w-10/12 flex flex-col">
                                            @foreach($daySchedule as $number => $numberSchedule)
                                                <div class="flex flex-row border-b border-solid border-black py-2
                                                    @if(last($daySchedule) == $numberSchedule) border-none @endif">
                                                    <div class="w-1/10 self-center">
                                                        {{ $number }}
                                                    </div>
                                                    <div class="w-9/10">
                                                        @if(array_key_exists('постійно', $numberSchedule))
                                                            {{ $numberSchedule['постійно'] }}
                                                        @else
                                                            {!! $numberSchedule['чисельник'] ?? '<b>Відсутньо</b>' !!}  /
                                                            {!! $numberSchedule['знаменник'] ?? '<b>Відсутньо</b>' !!}
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            @endif
            {{--            <div data-menu-section="notification-settings" class="hidden">--}}
            {{--                notification settings--}}
            {{--            </div>--}}
        </div>
    </div>

    @include('layouts.footer')
@endsection

@push('scripts')
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
@endpush
