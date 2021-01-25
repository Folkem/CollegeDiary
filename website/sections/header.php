<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";
?>

<header class="header">
    <?php if (is_null($currentUser)): ?>
        <div class="login-menu-cover hidden" id="login-menu">
            <div class="login-menu-main">
                <h1 class="login-menu-header">Авторизація</h1>
                <span class="login-menu-status-message" id="login-menu-status-message">
            </span>
                <form class="login-form" id="login-form" onsubmit="return false;">
                    <label class="login-form__label">
                        <input class="login-form__input" type="email"
                               id="email" required placeholder="Пошта">
                    </label>
                    <label class="login-form__label">
                        <input class="login-form__input" type="password"
                               id="password" required placeholder="Пароль">
                    </label>
                    <label class="login-form__label">
                        <button class="login-form__button" type="submit">Авторизуватися</button>
                    </label>
                </form>
                <button class="reset-password-button" id="reset-password-button">Скинути пароль</button>
            </div>
        </div>
    <?php endif; ?>
    <nav class="header__menu">
        <a class="link header__element" href="https://www.college.uzhnu.edu.ua/">
            <img class="header__image" src="/media/util/ic_college.png" alt="Герб колледжу">
        </a>
        <a class="link header__element header__element-hoverable" href="/pages/about.php">
            <span class="header__text">Про ресурс</span>
        </a>
        <a class="link header__element header__element-hoverable" href="/pages/news.php">
            <span class="header__text">Новини</span>
        </a>
        <a class="link header__element header__element-hoverable" href="/pages/contact.php">
            <span class="header__text">Зворотній зв'язок</span>
        </a>
        <div class="header__element">
            <?php if (is_null($currentUser)): ?>
                <div class="header__element header__element-hoverable link"
                     id="show-login-menu-button">
                    <p class="header__text">Авторизація</p>
                </div>
            <?php else: ?>
                <div class="link profile" id="toggle-user-menu-button">
                    <div class="profile__element">
                        <img class="profile__avatar header__image"
                             src="/media/user_avatars/<?= $currentUser->getAvatarPath() ?>"
                             alt="Зображення профілю">
                    </div>
                    <div class="profile__element profile__text">
                        <p class="profile__menu-text">Меню</p>
                        <i class="fa fa-caret-left profile__menu-caret" id="profile-caret"></i>
                        <div class="profile__dropdown hidden" id="profile-dropdown">
                            <ul class="undecorated-list">
                                <li>
                                    <a class="link" href="/pages/profile.php">Перейти до профілю</a>
                                </li>
                                <?php if ($currentUser->getRole() === 'Адміністратор' ||
                                    $currentUser->getRole() === 'Завідувач відділення'): ?>
                                    <li>
                                        <a class="link" href="/pages/control_panel.php">Панель управління</a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <p class="link" id="user-exit-button">Вийти</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</header>