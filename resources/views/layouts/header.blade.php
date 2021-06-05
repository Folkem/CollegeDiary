@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endpush
<header class="w-full sticky top-0 z-10 bg-white shadow-xl">
    @guest
        <div id="header-auth-menu"
             class="z-20 absolute hidden w-full h-screen bg-blue-400 bg-opacity-80 flex">
            <div class="bg-white rounded-lg flex flex-col overflow-hidden m-auto w-full
            sm:w-3/4 md:w-7/12 lg:w-500 h-fit">
                <div class="text-xl sm:text-2xl relative md:text-3xl lg:text-4xl font-gotham-pro-bold
                    h-1/6 flex align-middle bg-blue-800">
                    <div class="text-center m-auto text-white p-8 cursor-pointer">
                        АВТОРИЗАЦІЯ
                    </div>
                    <div class="absolute h-full right-8 top-0 flex">
                        <i class="fas fa-solid fa-times text-white m-auto cursor-pointer"
                           id="header-hide-auth-button"></i>
                    </div>
                </div>
                <div class="h-5/6 flex font-gotham-pro p-8">
                    <div class="m-auto flex flex-col w-10/12">
                        <form action="{{ route('login') }}" class="text-xl sm:text-2xl flex flex-col gap-6"
                              autocomplete="on" method="post" onsubmit="return attemptLogin();">
                            @csrf
                            <div class="flex flex-col w-full gap-4">
                                <label for="login-email" class="text-center text-blue-600">
                                    Пошта
                                </label>
                                <input type="email" id="login-email" name="email" placeholder="Пошта" required
                                       class="bg-gray-200 border-solid border border-gray-600 text-gray-600
                                        rounded-full w-full px-6 py-1 font-gotham-pro" autocomplete="email">
                            </div>
                            <div class="flex flex-col w-full gap-4">
                                <label for="login-password" class="text-center text-blue-600">
                                    Пароль
                                </label>
                                <input type="password" id="login-password" name="password" placeholder="Пароль" required
                                       class="bg-gray-200 border-solid border border-gray-600 text-gray-600
                                        rounded-full w-full px-6 py-1 font-gotham-pro" autocomplete="password">
                            </div>
                            <div class="self-center mt-4">
                                <button type="submit" class="border-3 rounded-full border-solid border-blue-800
                                font-gotham-pro-bold text-blue-800 px-12 py-1 text-center">
                                    Вхід
                                </button>
                            </div>
                        </form>
                        <a href="{{--route('password.request')--}}" class="text-center mx-auto mt-8 text-xl">
                            Забули пароль?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endguest
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
                   class="ml-4 inline-block font-museo-cyrl text-xl md:text-3xl w-1/2 sm:w-full">
                    Онлайн-щоденник ПГФК
                </a>
            </div>
            <div class="relative right-0 hidden lg:block font-gotham-pro-bold text-lg uppercase">
                <nav>
                    <ul class="mt-7 mb-3 flex justify-end first:ml-0">
                        @auth
                            @if(auth()->user()->role->name === 'admin')
                                <li class="ml-7">
                                    <a href="#">Адмін-панель</a>
                                </li>
                            @endif
                            <li class="ml-7">
                                <a href="#">{{ auth()->user()->name }}</a>
                            </li>
                            <li class="ml-7">
                                <form action="{{ route('logout') }}" method="post" class="p-0 m-0">
                                    @csrf
                                    <button type="submit" class="font-gotham-pro-bold text-lg uppercase">
                                        Вийти
                                    </button>
                                </form>
                            </li>
                        @endauth
                        @guest
                            <li class="ml-7">
                                <div data-id="header-show-auth-button" class="cursor-pointer">АВТОРИЗАЦІЯ</div>
                            </li>
                        @endguest
                    </ul>
                </nav>
                <nav>
                    <ul class="flex justify-end first:ml-0">
                        <li class="ml-7">
                            <a href="#">Розклад</a>
                        </li>
                        <li class="ml-7">
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
         flex text-center shadow-inner font-gotham-pro-bold text-white text-4xl
            hidden lg:hidden">
        <ul class="m-auto space-y-6">
            @if(auth()->check() && auth()->user()->role->name === 'admin')
                <li>
                    <a href="">Адмін-панель</a>
                </li>
            @endif
            @auth
                <li>
                    <a href="#">{{ auth()->user()->name }}</a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="post" class="p-0 m-0">
                        @csrf
                        <button type="submit" class="font-gotham-pro-bold text-white text-4xl">
                            Вийти
                        </button>
                    </form>
                </li>
            @endauth
            @guest
                <li class="drop-shadow-xl">
                    <div data-id="header-show-auth-button" class="cursor-pointer">Авторизація</div>
                </li>
            @endguest
            <li class="drop-shadow-xl">
                <a href="#">Розклад</a>
            </li>
            <li class="drop-shadow-xl">
                <a href="{{ route('news.index') }}">Новини</a>
            </li>
        </ul>
    </div>
</header>

@push('scripts')
    <script src="https://kit.fontawesome.com/50da9c50c1.js" crossorigin="anonymous"></script>
    <!--suppress JSUnfilteredForInLoop -->
    <script>
        const hamburgerButton = document.querySelector('#header-hamburger-button');
        const hamburgerIcon = document.querySelector('#header-hamburger-icon');
        const dropdownMenu = document.querySelector('#header-dropdown-menu');

        hamburgerButton.addEventListener('click', () => {
            hamburgerIcon.classList.toggle('tham-active');
            dropdownMenu.classList.toggle('hidden');
            if (authMenu.classList.contains('hidden')) {
                document.body.classList.toggle('overflow-hidden');
            }
        });

        @guest

        const showAuthButtons = document.querySelectorAll('[data-id="header-show-auth-button"]');
        const hideAuthButton = document.querySelector('#header-hide-auth-button');
        const authMenu = document.querySelector('#header-auth-menu');

        showAuthButtons.forEach(button => {
            button.addEventListener('click', () => {
                authMenu.classList.toggle('hidden');
                if (dropdownMenu.classList.contains('hidden')) {
                    document.body.classList.toggle('overflow-hidden');
                }
            });
        });

        hideAuthButton.addEventListener('click', () => {
            authMenu.classList.toggle('hidden', true);
            if (dropdownMenu.classList.contains('hidden')) {
                document.body.classList.toggle('overflow-hidden', false);
            }
        });

        function attemptLogin() {
            fetch('{{ route('login') }}', {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    'email': document.querySelector('#login-email').value,
                    'password': document.querySelector('#login-password').value,
                }),
            }).then(result => {
                console.log(`${result.status}`);
                if (['200', '422'].includes(`${result.status}`)) {
                    console.log('ha');
                    return result.json();
                } else {
                    console.log('eh');
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
                console.log('Помилка авторизації');
                console.error(e);
                alert('Серверна помилка');
            });

            return false;
        }

        @endguest
    </script>
@endpush
