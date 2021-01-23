<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
?>

<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/normalize.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/general.css">
    <link rel="stylesheet" href="/styles/error.css">
    <title>Помилка</title>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="error-main">
    <h1 class="error-message">Запрошена вами сторінка не була знайдена</h1>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>
</html>