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

    //подключаем роуты
    $routes = require __DIR__ . '/config/routes.php';
    $controller = '';
    foreach ($routes as $route) {
        //проверяем на совпадение урл с роутами
        if (preg_match($route['url'], $partsPath['path'])) {
            //если совпадение найдено прерываем цикл
            //и записываем имя контроллера в переменную
            $controller = $route['controller'];
            break;
        }
    }

    //если контроллер не найден, тогда 404 ошибка
    if (empty($controller)) {
        $code = 404;
        //устанавливаем код ответа HTTP
        http_response_code($code);
        //поключаем шаблон ошибки по коду
        require __DIR__ . "/мiews/errors/{$code}.php";
        die;
    }

    //подключаем конфиг бд и считываем массив в переменную
    $config = require __DIR__ . '/config/db.php';
    //создаем подключение к бд
    $PDODriver = connectDB($config);

    //подключаем контроллер отвечающий за обработку http запроса
    require __DIR__ . "/controllers/{$controller}_controller.php";
    //подключаем основной шаблон клиента
    require __DIR__ . "/views/layouts/default.php";
} catch (Exception $e) {
    //вывод ошибок
    die ($e->getMessage());
}