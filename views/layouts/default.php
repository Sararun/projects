<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TO-DO LIST</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="wrapper">
        <?php require __DIR__ . '/../blocks/_navbar.php'; ?>
        <?php require __DIR__ . '/../blocks/_alert.php'; ?>
        <div class="row">
            <?php echo $content; ?>
        </div>
    </div>
    <?php require __DIR__ . '/../blocks/_footer.php'; ?>
</div>
<script src="/assets/js/jquery-1.12.4.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/script.js"></script>
</body>
</html>