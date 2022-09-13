<?php
/** @var $currentController */

if (empty($_SESSION['success_register'])) {
    $code = 404;
    //устанавливаем код ответа HTTP и подключаем шаблон ошибки по код
    dispatchNotFound($code);
}

//записываем в подключаемый вид для подстановки в шаблон
$content = render("/auth/{$currentController}");
