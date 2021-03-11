<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/font-awesome/all.min.css">
    <link rel="stylesheet" href="../css/util/reset.css">
    <link rel="stylesheet" href="../css/util/normalize.css">
    <link rel="stylesheet" href="../css/sections.css">
    <link rel="stylesheet" href="../css/util/general.css">
    <link rel="stylesheet" href="../css/error.css">
    <script src="../js/sections.js"></script>
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