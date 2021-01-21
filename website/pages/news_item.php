<?php

require_once "../util/loader.php";
require_once "../util/auth_check.php";
require_once "../util/logging.php";

$item = null;
$redirect = true;

if (isset($_GET['item'])) {
    try {
        $itemId = intval($_GET['item']);

        StorageRepository::load();
        $newsArray = StorageRepository::getNews();

        $filteredNews = array_filter($newsArray, function($newsItem) use($itemId) {
            return ($newsItem->getId() === $itemId);
        });

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
    header("Location: /pages/news.php");
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
    <link rel="stylesheet" href="../styles/normalize.css">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/sections.css">
    <link rel="stylesheet" href="../styles/general.css">
    <link rel="stylesheet" href="../styles/news-item.css">
    <script src="../scripts/sections.js"></script>
</head>
<body>
<?php require_once "../sections/header.php"; ?>

<main class="main news-item">
    <h1 class="news-item__header"><?= $item->getHeader() ?></h1>
    <p class="news-item__date">
        <?= $item->getDate()->format('Y/m/d — H:i:s') ?>
    </p>
    <p class="news-item__text">
        <?= $item->getText() ?>
    </p>
</main>

<?php require_once "../sections/footer.php"; ?>
</body>