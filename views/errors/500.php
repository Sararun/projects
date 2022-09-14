<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Internal Server Error (#500)</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<div class="container">
    <div class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        Internal Server Error (#500)
                        <hr>
                        <?php /** @var Exception $e */?>
                        <?php echo $e->getMessage();?>
                        <hr>
                        <a href="/">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>