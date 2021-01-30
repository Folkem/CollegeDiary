<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/util/loader.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/auth_check.php';

if (isset($_POST['news-item-id'], $_POST['comment-text'], $currentUser)) {
    $newsItemId = intval($_POST['news-item-id']);
    $commentText = $_POST['comment-text'];

    $message = '';
    $commentWasAdded = false;
    $commentId = -1;
    $commentPostDate = date('Y/m/d — H:m:s');
    $userFullName = $currentUser->getFullName();
    $commentUserData = ['fullName' => $userFullName];

    $userId = $currentUser->getId();

    $newsComment = new NewsComment();
    $newsComment->setUser($currentUser)
        ->setPublishDate(DateTimeImmutable::createFromFormat('Y/m/d — H:m:s', $commentPostDate))
        ->setNewsId($newsItemId)
        ->setComment($commentText);

    $commentWasAdded = NewsRepository::addNewsComment($newsComment);
    $commentId = $newsComment->getId();

    if (!$commentWasAdded) {
        $message = 'Неочікувана помилка';
    }

    $result = [
        'message' => $message,
        'commentWasAdded' => $commentWasAdded,
        'commentContent' => [
            'id' => $commentId,
            'postDate' => $commentPostDate,
            'userData' => $commentUserData,
            'commentText' => htmlentities($commentText)
        ]
    ];

    echo json_encode($result);
} else {
    header('Location: /');
}