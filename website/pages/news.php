<?php
require_once "../util/loader.php";
require_once "../util/auth_check.php";
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Новини — Онлайн-щоденник</title>
    <link rel="stylesheet" href="../styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="../styles/normalize.css">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/sections.css">
    <link rel="stylesheet" href="../styles/general.css">
    <link rel="stylesheet" href="../styles/news.css">
    <script src="../scripts/sections.js"></script>
</head>
<body>
<?php require_once "../sections/header.php"; ?>

<main class="main">
    <?php
    // TODO: make news system look cute and effective
    StorageRepository::load();

    $newsArray = StorageRepository::getNews();

    foreach ($newsArray as $item) { ?>
        <div class="news-item">
            <a class="link" href="/pages/news_item.php?item=<?= $item->getId() ?>">
                <h1><?= $item->getHeader(); ?></h1>
                <p>
                    <?= $item->getText(); ?>
                </p>
                <div>
                    <?= $item->getDate()->format('j.m.Y'); ?>
                </div>
            </a>
        </div>
    <?php }
    ?>
</main>

<?php require_once "../sections/footer.php"; ?>
</body>