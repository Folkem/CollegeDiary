<?php require_once '../util/auth_check.php'; ?>

<header class="header">
    <nav class="header__menu">
        <a class="link header__element" href="https://www.college.uzhnu.edu.ua/">
            <img class="header__image" src="../media/util/ic_college.png" alt="Герб колледжу">
        </a>
        <!-- !!! -->
        <a class="link header__element header__element-hoverable" href="/pages/about.php">
            <span class="header__text">Про ресурс</span>
        </a>
        <a class="link header__element header__element-hoverable" href="/pages/news.php">
            <span class="header__text">Новини</span>
        </a>
        <!-- !!! -->
        <a class="link header__element header__element-hoverable" href="/pages/contact.php">
            <span class="header__text">Зворотній зв'язок</span>
        </a>
        <div class="header__element">
            <?php if (is_null($currentUser)): ?>
                <form class="header-form" onsubmit="return headerAuthorize()">
                    <label class="header-form__label">
                        <input type="email" required placeholder="Пошта">
                    </label>
                    <label class="header-form__label">
                        <input type="password" required placeholder="Пароль">
                    </label>
                    <label class="header-form__label">
                        <button type="submit">Авторизуватися</button>
                    </label>
                </form>
            <?php else: ?>
                <img src="/media/user_avatars/<?= $currentUser->getAvatarPath() ?>"
                     alt="Зображення профілю" class="header__image">
                <span class="header__text profile">
                    <a class="link" href="/pages/profile.php">
                        <span class="header__element-hoverable">
                        Вітаємо, <?= $currentUser->getFirstName(); ?>!
                        </span>
                    </a>
                    <span class="user-exit-button">
                    Бажаєте вийти?
                    </span>
                </span>
            <?php endif; ?>
        </div>
        <!--        <button class="header__element open-login-button">-->
        <!--            <span class="header__text">Авторизація</span>-->
        <!--        </button>-->
    </nav>
</header>