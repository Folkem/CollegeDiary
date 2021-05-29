<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php"; ?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Новини — Онлайн-щоденник</title>
    <link rel="stylesheet" href="../css/font-awesome/all.min.css">
    <link rel="stylesheet" href="../css/util/normalize.css">
    <link rel="stylesheet" href="../css/util/reset.css">
    <link rel="stylesheet" href="../css/sections.css">
    <link rel="stylesheet" href="../css/util/general.css">
    <link rel="stylesheet" href="../css/news.css">
    <script src="../js/sections.js"></script>
    <script src="../js/news.js"></script>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="wrapper">
    <div class="news" id="news">
        <?php
        $itemTextMaxLength = 300;
        $imagesPath = $_SERVER["DOCUMENT_ROOT"] . "/media/news_images/";

        $newsArray = NewsRepository::getNews();
        usort(
            $newsArray,
            function ($firstNewsItem, $secondNewsItem) {
                $firstDate = $firstNewsItem->getDate();
                $secondDate = $secondNewsItem->getDate();

                $difference = $firstDate->diff($secondDate);

                if ($difference->days == 0) {
                    return 0;
                } elseif ($difference->invert == 0) {
                    return 1;
                } else {
                    return -1;
                }
            }
        );

        foreach ($newsArray as $item): ?>

            <div class="news-item">
                <a class="link news-item-link" href="/pages/news-item.php?item=<?= $item->getId() ?>">
                    <?php if (file_exists($imagesPath . $item->getImagePath())): ?>

                        <div class="news-item__image-block">
                            <img alt="Зображення новини" class="news-item__image"
                                 src="/media/news_images/<?= $item->getImagePath() ?>">
                        </div>

                    <?php endif; ?>

                    <div class="news-item__content">
                        <div class="news-item__publishing-date">
                            <?= $item->getDate()->format('Y-m-d'); ?>
                        </div>
                        <h1 class="news-item__header"><?= $item->getHeader(); ?></h1>
                        <div class="news-item__keywords">
                            <?php
                            $keywords = $item->getKeywords();
                            if (count($keywords) > 0) {
                                $keywords = array_map(fn($keyword) => "<b>$keyword</b>", $keywords);
                                echo 'Ключові слова: ' . implode(', ', $keywords);
                            }
                            ?>
                        </div>
                        <div class="news-item__text">
                            <?php
                            if (mb_strlen($item->getText()) <= $itemTextMaxLength) {
                                echo $item->getText();
                            } else {
                                echo mb_substr($item->getText(), 0, $itemTextMaxLength) . "...";
                            }
                            ?>
                        </div>
                    </div>
                </a>
            </div>

        <?php endforeach; ?>
    </div>
    <div class="news-filter">
        <h2 class="news-filter__header">Пошук</h2>
        <label class="news-filter__label" for="news-name-input">
            Назва шуканої новини:
        </label>
        <input id="news-name-input" class="news-filter__input"
               type="text" placeholder="Назва">
        <label class="news-filter__label" for="news-date-start">
            Початок:
        </label>
        <input id="news-date-start" class="news-filter__input"
               type="date">
        <label class="news-filter__label" for="news-date-end">
            Кінець:
        </label>
        <input id="news-date-end" class="news-filter__input"
               type="date">
        <label class="news-filter__label" for="news-keywords">
            Ключові слова:
        </label>
        <div class="news-filter__label">
            <div id="news-keywords"
                 class="news-filter__input"
                 contenteditable="true">

            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>
</html>