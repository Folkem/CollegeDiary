<header class="w-full sticky top-0 z-20 bg-white h-28 ">
    <div class="overflow-hidden max-w-1200 mx-auto px-5">
        <section class="h-28 flex justify-between">
            <div class="relative left-0 flex items-center">
                <figure class="h-20 w-20">
                    <img class="w-full h-full object-contain" src="{{ asset('media/static/pgdk_logo.png') }}" width="80px" alt="Логотип ПГФК">
                </figure>
                <a href="" class="ml-4 inline-block font-museo-cyrl text-3xl">ПГФК</a>
            </div>
            <div class="relative right-0 sm:invisible ">
                <nav class="">
                    <ul class="mt-7 mb-3 flex justify-end items-center first:ml-0">
                        <li class="ml-7 text-blue-600 font-museo-cyrl text-xl">
                            <a href="">Про ресурс</a>
                        </li>
                        <li class="ml-7 text-blue-600 font-museo-cyrl text-xl">
                            <a href="">Події</a>
                        </li>
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
                            <a href="">Студенту</a>
                        </li>
                        <li class="ml-7 font-gotham-pro-bold text-lg uppercase">
                            <a href="{{ route('news.index') }}">Новини</a>
                        </li>
                        <!-- <button class="page-link search-button">О</button> -->
                    </ul>
                </nav>
            </div>
        </section>
    </div>
</header>
