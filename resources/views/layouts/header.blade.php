<header class="w-full sticky top-0 z-10 bg-white shadow-xl">
    <div class="overflow-hidden max-w-1200 mx-auto">
        <section class="pt-2 pb-6 flex justify-between px-5 shadow-xl">
            <div class="relative left-0 flex items-center">
                <figure class="h-16 w-16 md:h-20 md:w-20">
                    <a href="https://www.college.uzhnu.edu.ua/">
                        <img class="w-full h-full object-contain" src="{{ asset('media/static/pgdk_logo.png') }}"
                             width="80px" alt="Логотип ПГФК">
                    </a>
                </figure>
                <a href="{{ route(\App\Providers\RouteServiceProvider::HOME) }}"
                   class="ml-4 inline-block font-museo-cyrl text-xl md:text-3xl w-1/2 sm:w-full">Онлайн-щоденник
                    ПГФК</a>
            </div>
            <div class="relative right-0 hidden lg:block">
                <nav class="">
                    <ul class="mt-7 mb-3 flex justify-end items-center first:ml-0">
                        @if(auth()->check() && auth()->user()->role->name === 'admin')
                            <li class="ml-7 text-black font-gotham-pro-bold text-l">
                                <a href="">АДМІН-ПАНЕЛЬ</a>
                            </li>
                        @endif
                        <li class="ml-7 text-black font-gotham-pro-bold text-l">
                            <a href="">АВТОРИЗАЦІЯ</a>
                        </li>
                    </ul>
                </nav>
                <nav class="">
                    <ul class="flex items-center first:ml-0">
                        <li class="ml-7 font-gotham-pro-bold text-lg uppercase">
                            <a href="">Розклад</a>
                        </li>
                        <li class="ml-7 font-gotham-pro-bold text-lg uppercase">
                            <a href="{{ route('news.index') }}">Новини</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="flex lg:hidden relative flex flex-col justify-center" id="header-hamburger-button">
                <div class="tham tham-e-squeeze tham-w-8 md:tham-w-12" id="header-hamburger-icon">
                    <div class="tham-box">
                        <div class="tham-inner"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div id="header-dropdown-menu"
         class="z-10 absolute w-screen h-screen bg-blue-300 bg-opacity-80
         flex text-center shadow-inner font-gotham-pro-bold text-white text-6xl
            hidden lg:hidden">
        <ul class="m-auto space-y-6">
            @if(auth()->check() && auth()->user()->role->name === 'admin')
                <li class="">
                    <a href="">АДМІН-ПАНЕЛЬ</a>
                </li>
            @endif
            <li class="drop-shadow-xl">
                <a href="" id="header-show-auth-button">Авторизація</a>
            </li>
            <li class="drop-shadow-xl">
                <a href="">Розклад</a>
            </li>
            <li class="drop-shadow-xl">
                <a href="{{ route('news.index') }}">Новини</a>
            </li>
        </ul>
    </div>
</header>

@push('scripts')
    <script>
        const hamburgerButton = document.querySelector('#header-hamburger-button');
        const hamburgerIcon = document.querySelector('#header-hamburger-icon');
        const dropdownMenu = document.querySelector('#header-dropdown-menu');

        hamburgerButton.addEventListener('click', () => {
            hamburgerIcon.classList.toggle('tham-active');
            dropdownMenu.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        });
    </script>
@endpush
