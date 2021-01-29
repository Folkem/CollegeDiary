<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";

if (!isset($currentUser)) {
    header("Location: /");
    return;
}

setlocale(LC_ALL, 'uk_UA.UTF-8');

$notificationCount = StorageRepository::
getUnreadNotificationCountForUser($currentUser->getId());

?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Повідомлення <?= $notificationCount > 0 ? "($notificationCount)" : '' ?>
        — Онлайн-щоденник</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/normalize.css">
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/notifications.css">
    <link rel="stylesheet" href="/styles/general.css">
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/notifications.js"></script>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="pop-up-message hidden" id="pop-up-message">
    <div class="pop-up-message__content" id="pop-up-message-content">
    </div>
</div>
<div class="wrapper">
    <div class="notifications">
        <div class="controls">
            <button class="control-item" id="read-all-button">
                Відмітити всі прочитанними
            </button>
            <button class="control-item" id="delete-all-button">
                Видалити всі
            </button>
        </div>
        <div class="notification-list" id="notification-list">
            <?php
            $notificationList = StorageRepository::getNotificationsForUser($currentUser->getId());
            foreach ($notificationList as $notification):
                $id = $notification->getId();
                $comment = $notification->getComment();
                $link = $notification->getLink();
                $isRead = $notification->isRead();
                $date = $notification->getDate();
                ?>

                <div class="notification notification--<?= $isRead ? 'read' : 'unread' ?>">
                    <div class="notification__id">
                        <?= $id ?>
                    </div>
                    <div class="notification__date-pure">
                        <?= $date->format('Y/m/d') ?>
                    </div>
                    <div class="notification__date">
                        <?= strftime('%e %B %Y', strtotime($date->format('Y/m/d'))) ?>
                    </div>
                    <div class="notification__time">
                        <?= $date->format('H:i:s') ?>
                    </div>
                    <div class="notification__comment">
                        <?= $comment ?>
                    </div>
                    <a class="link notification__link"
                       href="<?= $link ?>">
                        Посилання
                    </a>
                    <div class="notification__buttons">
                        <?php if (!$isRead): ?>
                            <button class="notification__button read-button"
                                    title="Відмітити прочитанним">
                                <i class="fa fa-check-square fa-2x"></i>
                            </button>
                        <?php endif; ?>
                        <button class="notification__button delete-button"
                                title="Видалити повідомлення">
                            <i class="fa fa-trash fa-2x"></i>
                        </button>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>