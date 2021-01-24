<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";

if (is_null($currentUser) ||
    $currentUser->getRole() !== 'Адміністратор' &&
    $currentUser->getRole() !== 'Завідувач відділення') {
    header("Location: /");
    return;
}

?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Панель управління — Онлайн-щоденник</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/normalize.css">
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/general.css">
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/profile.js"></script>
</head>
<body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/header.php"; ?>

<div class="wrapper">

    тут пока что пусто

</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/sections/footer.php"; ?>
</body>