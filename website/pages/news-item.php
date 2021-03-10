<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

$item = null;
$redirect = true;

if (isset($_GET['item'])) {
    try {
        $itemId = intval($_GET['item']);

        $newsArray = NewsRepository::getNews();

        $filteredNews = array_filter($newsArray, function ($newsItem) use ($itemId) {
            return ($newsItem->getId() === $itemId);
        });
        $filteredNews = array_values($filteredNews);

        if (count($filteredNews) > 1) {
            $logger->error("found 2 news records with the same id");
        } else if (count($filteredNews) == 0) {
            $redirect = true;
        } else {
            $item = $filteredNews[0];
            $redirect = false;
        }
    } catch (Exception $exception) {
        $redirect = true;
    }
}

if ($redirect) {
    header("Location: /");
}
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $item->getHeader() ?> — Онлайн-щоденник</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/util/normalize.css">
    <link rel="stylesheet" href="/styles/util/reset.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/util/general.css">
    <link rel="stylesheet" href="/styles/news_item.css">
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/news_item.js"></script>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="wrapper">
    <div class="news-item">
        <div class="hidden" id="news-item__id"><?= $item->getId() ?></div>
        <h1 class="news-item__header"><?= $item->getHeader() ?></h1>
        <div class="news-item__date">
            <?= $item->getDate()->format('Y/m/d'/*' — H:i:s'*/) ?>
        </div>
        <div class="news-item__keywords">
            <?php
            $keywords = $item->getKeywords();
            if (count($keywords) > 0) {
                $keywords = array_map(fn($keyword) => "<b>$keyword</b>", $keywords);
                echo 'Ключові слова: ' . implode(', ', $keywords);
            }
            ?>
        </div>

        <?php
        $imagesPath = $_SERVER["DOCUMENT_ROOT"] . "/media/news_images/";
        if (file_exists($imagesPath . $item->getImagePath())): ?>

            <div class="news-item__image-block">
                <img alt="Зображення новини" class="news-item__image"
                     src="/media/news_images/<?= $item->getImagePath() ?>">
            </div>

        <?php endif; ?>

        <p class="news-item__text">
            <?= $item->getText() ?>
        </p>
    </div>
    <div class="comment-section">
        <div class="comment-list" id="comment-list">

            <?php
                $comments = NewsRepository::getNewsCommentsForItem($itemId);
                foreach ($comments as $comment):?>

                <div class="comment-item">
                    <div class="hidden comment-item__id">
                        <?= $comment->getId() ?>
                    </div>
                    <div class="comment-item__publish-date">
                        <?= $comment->getPublishDate()->format('Y/m/d — H:m:s') ?>
                    </div>
                    <div class="comment-item__user-data">
                        by <?= $comment->getUser()->getFullName() ?>
                    </div>
                    <div class="comment-item__comment-content">
                        <?= htmlentities($comment->getComment()) ?>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <?php if (isset($currentUser)): ?>

            <div class="comment-form-block">
                <div class="comment-form-response" id="comment-form-response"></div>
                <form class="comment-form" id="comment-form" onsubmit="return false;">
                    <div>Ваш коментар: </div>
                    <label for="comment-form-text">
                        <textarea class="comment-form-text" id="comment-form-text" rows="10"></textarea>
                    </label>
                    <button class="comment-form-button">Відправити</button>
                </form>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>
</html>