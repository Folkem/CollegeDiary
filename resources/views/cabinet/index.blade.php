@extends('layouts.app')

@section('title', 'Особистий кабінет')

@section('body')
    @include('layouts.header')

    <div class="max-w-1200 flex flex-col md:flex-row mx-auto">
        <div class="bg-red-400 font-museo-cyrl md:w-5/12 bg-blue-500 flex flex-col text-base sm:text-lg
        md:text-xl lg:text-3xl">
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white">
                Особистий кабінет
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white bg-white text-blue-900"
                 data-menu-button="profile">
                Ваш профіль
            </div>
            <hr>
            <div class="text-white px-8 py-4 border-solid border-b-2 border-white"
                 data-menu-button="lesson-schedule">
                Розклад
            </div>
            <div class="text-white px-8 py-4 md:border-solid border-b-2 border-white"
                 data-menu-button="notification-settings">
                Налаштування повідомлень
            </div>
        </div>
        <div class="bg-blue-100 md:w-7/12 flex">
            <div data-menu-section="profile" class="px-12 py-6">
                <div class="font-gotham-pro-bold text-blue-900 text-3xl mb-8">
                    Зміна паролю
                </div>
                <form action="{{-- route('cabinet.password') --}}" method="post"
                    class="flex flex-col gap-2">
                    @csrf
                    @method('put')
                    <div class="flex flex-col gap-2">
                        <label for="previous-password" class="font-museo-cyrl text-indigo-700 text-base">
                            Введіть попередній пароль
                        </label>
                        <input type="password" id="previous-password" name="previous-password"
                            class="px-2 rounded-full border-solid border-2 border-blue-900 text-base">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="password" class="font-museo-cyrl text-indigo-700 text-base">
                            Введіть попередній пароль
                        </label>
                        <input type="password" id="password" name="password"
                            class="px-2 rounded-full border-solid border-2 border-blue-900 text-base">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="password-confirmation" class="font-museo-cyrl text-indigo-700 text-base">
                            Введіть попередній пароль
                        </label>
                        <input type="password" id="password-confirmation" name="password-confirmation"
                            class="px-2 rounded-full border-solid border-2 border-blue-900 text-base">
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="font-museo-cyrl text-xl bg-green-500
                            hover:bg-green-600 px-8 py-1 text-white rounded-full">
                            Зберегти
                        </button>
                    </div>
                </form>
            </div>
            <div data-menu-section="lesson-schedule" class="hidden">
                lesson schedule
            </div>
            <div data-menu-section="notification-settings" class="hidden">
                notification settings
            </div>
        </div>
    </div>

    @include('layouts.footer')
@endsection

@section('scripts')
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
@endsection
