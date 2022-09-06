<?php
/** @var $controller */

if (empty($_SESSION['success_register'])) {
    $code = 404;
    //устанавливаем код ответа HTTP
    http_response_code($code);
    //подключаем шаблон ошибки по коду
    require __DIR__ . "/../views/errors/{$code}.php";
    die;
}

//записей в подключаемый вид для подстановке в шаблоне
$content = render("/auth/{$controller}");
