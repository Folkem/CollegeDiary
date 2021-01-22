<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/util/loader.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/auth_check.php";

if (is_null($currentUser)) {
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
    <title>Профіль користувача</title>
    <link rel="stylesheet" href="/styles/font-awesome/all.min.css">
    <link rel="stylesheet" href="/styles/normalize.css">
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/sections.css">
    <link rel="stylesheet" href="/styles/general.css">
    <script src="/scripts/sections.js"></script>
    <script src="/scripts/profile.js"></script>
</head>
<body>
<?php require_once "../sections/header.php"; ?>

<?php
StorageRepository::load();
?>

<div>
    <h3>password change</h3>
    <form onsubmit="return saveChanges();">
        <input id="currentPassword" type="password" placeholder="Поточний пароль" required>
        <input id="newPassword" type="password" placeholder="Новий пароль" required>
        <input id="repeatedNewPassword" type="password" placeholder="Повторіть новий пароль" required>
        <button type="submit">Підтвердіть зміни</button>
    </form>
    <h3>avatar change</h3>
    <form onsubmit="return saveChanges();">
        <input id="newAvatarFile" type="file" required>
        <button type="submit">Підтвердіть зміни</button>
    </form>
</div>

<?php require_once "../sections/footer.php"; ?>
</body>