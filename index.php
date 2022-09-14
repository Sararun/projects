<?php
declare(strict_types=1);
error_reporting(-1);
session_start();
try {
    //подключаем главную модель
    require __DIR__ . '/models/app_model.php';

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
    $currentPath = $partsPath['path'] ?? '';

    //подключаме роуты
    if (empty($_SESSION['user'])) {
        $currentController = 'login';
    } else {
        $routes = require __DIR__ . '/config/routes.php';
        $currentController = '';
        foreach ($routes as $route) {
            //проверяем на совпадение урл с роутами
            if (preg_match($route['url'], $currentPath)) {
                //если совпадение найдено прерываем цикл
                //и записываем имя контроллера в переменную
                $currentController = $route['controller'];
                break;
            }
        }
    }

    //если контроллер не найден, тогда 404 ошибка
    if (empty($controller)) {
        throw new \PDOException("Page not found (#404) ", 404);
    }

    //создаем подключение к бд
    $PDODriver = connectDB();

    //подключаем контроллер отвечающий за обработку http запроса
    require __DIR__ . "/controllers/{$currentController}_controller.php";
    //подключаем основной шаблон клиента
    require __DIR__ . "/views/layouts/default.php";
} catch (Exception $e) {
    $code = (int)$e->getCode() ?? 404;
    //поключаем шаблон ошибки по коду
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $error = date('d.m.Y H:i:s') . "|{$e->getMessage()}|$ip|$userAgent\n";
    file_put_contents(__DIR__ . '/error.txt', $error, FILE_APPEND);
    dispatchNotFound($code);
    die;
}