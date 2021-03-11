<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

if (is_null($currentUser)) {
    header("Location: /");
    return;
}

setlocale(LC_ALL, 'uk_UA.UTF-8');

$notificationCount = NotificationRepository::
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
    <link rel="stylesheet" href="../css/font-awesome/all.min.css">
    <link rel="stylesheet" href="../css/util/normalize.css">
    <link rel="stylesheet" href="../css/util/reset.css">
    <link rel="stylesheet" href="../css/sections.css">
    <link rel="stylesheet" href="../css/notifications.css">
    <link rel="stylesheet" href="../css/util/general.css">
    <script src="../js/sections.js"></script>
    <script src="../js/notifications.js"></script>
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
            $notificationList = NotificationRepository::getNotificationsForUser($currentUser->getId());
            foreach ($notificationList as $notification):
                $id = $notification->getId();
                $comment = $notification->getComment();
                $link = $notification->getLink();
                $isRead = $notification->isRead();
                $publishDate = $notification->getPublishDate();
                ?>

                <div class="notification notification--<?= $isRead ? 'read' : 'unread' ?>">
                    <div class="notification__id">
                        <?= $id ?>
                    </div>
                    <div class="notification__publish-date-pure">
                        <?= $publishDate->format('Y/m/d') ?>
                    </div>
                    <div class="notification__publish-date">
                        <?= strftime('%e %B %Y', strtotime($publishDate->format('Y/m/d'))) ?>
                    </div>
                    <div class="notification__time">
                        <?= $publishDate->format('H:i:s') ?>
                    </div>
                    <div class="notification__comment">
                        <?= $comment ?>
                    </div>
                    <a class="link notification__link <?= is_null($link) ? 'invisible' : '' ?>"
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
</html>