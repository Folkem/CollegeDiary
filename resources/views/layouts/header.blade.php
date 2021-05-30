<header class="header">
    <div class="header-wrapper">
        <section class="header-body body">
            <div class="logo-part">
                <figure class="logo-placeholder">
                    <img class="logo" src="{{ asset('media/static/pgdk_logo.png') }}" width="80px" alt="Логотип ПГФК">
                </figure>
                <a href="" class="logo-text">ПГФК</a>
            </div>
            <div class="aside-part">
                <nav class="high-row">
                    <ul class="actions-navigation">
                        <li class="action-link">
                            <a href="">Про ресурс</a>
                        </li>
                        <li class="action-link">
                            <a href="">Події</a>
                        </li>
                        <li class="action-link authorization-link">
                            <a href="">Авторизація</a>
                        </li>
                    </ul>
                </nav>
                <nav class="low-row">
                    <ul class="pages-navigation">
                        <li class="page-link">
                            <a href="">Розклад</a>
                        </li>
                        <li class="page-link">
                            <a href="">Студенту</a>
                        </li>
                        <li class="page-link">
                            <a href="{{ route('news.index') }}">Новини</a>
                        </li>
                        <!-- <button class="page-link search-button">О</button> -->
                    </ul>
                </nav>
            </div>
        </section>
    </div>
</header>
