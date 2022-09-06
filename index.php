<?php
declare(strict_types=1);
error_reporting(-1);
session_start();
try {
    //подключаем главную модель
    require __DIR__ . '/Models/app_model.php';

    //устанавливаем токен для защиты запроса от подделки
    createCSRF();
    //проверяем подлинность токена
    checkCSRF();

    //получаем урл маршрута
    //REQUEST_URI - URI, который был предоставлен для доступа к этой странице. Например, '/index.html
    $url = trim($_SERVER['REQUEST_URI'], '/');
    //разбирает URL и возвращает его компоненты
    //[path] => / [query] => id=1
    $partsPath = parse_url($url);
    $stringPath = $partsPath['path'] ?? '';

    //подключаме роуты
    if (empty($_SESSION['user'])) {
        $controller = 'login';
    } else {
        $routes = require __DIR__ . '/config/routes.php';
        $controller = '';
        foreach ($routes as $route) {
            //проверяем на совпадение урл с роутами
            if (preg_match($route['url'], $stringPath)) {
                //если совпадение найдено прерываем цикл
                //и записываем имя контроллера в переменную
                $controller = $route['controller'];
                break;
            }
        }
    }
    //если контроллер не найден, тогда 404 ошибка
    if (empty($controller)) {
        throw new \PDOException("Page not found (#404) ", 404);
    }

    //подключаем конфиг бд и считываем массив в переменную
    $config = require __DIR__ . '/config/db.php';
    //создаем подключение к бд
    $PDODriver = connectDB($config);

    //подключаем контроллер отвечающий за обработку http запроса
    require __DIR__ . "/Controllers/{$controller}_controller.php";
    //подключаем основной шаблон клиента
    require __DIR__ . "/Views/layouts/default.php";
} catch (Exception $e) {
    $code = $e->getCode() ?? 404;
    //устанавливаем код ответа HTTP
    http_response_code((int)$code);
    //подключаем шаблон ошибки по коду
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $error = date('d.m.Y H:i:s') . "|{$e->getMessage()}|$ip|$userAgent\n";
    file_put_contents(__DIR__ . '/error.txt', $error, FILE_APPEND);
    require __DIR__ . "/Views/errors/{$code}.php";
    die;
}