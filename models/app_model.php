<?php

use JetBrains\PhpStorm\NoReturn;
/**
 * функция отладки (Debug)
 *
 * @param $data
 */
function dump($data)
{
    echo '<pre>'; var_dump($data); echo '</pre>';
}

/**
 * подключение к бд
 *
 * @return PDO
 */
function connectDB(): \PDO
{
    static $dbh = null;

    if (!is_null($dbh)) {
        return $dbh;
    }

    try {
        //подключаем конфиг бд и считываем массив в переменную
        $config = require  __DIR__ . '/../config/db.php';
        $dbh = new \PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
            $config['username'],
            $config['password'],
            $config['options']
        );

        return $dbh;
    } catch (\PDOException $e) {
        throw new \PDOException("Internal Server Error: {$e->getMessage()}", 500);
    }
}

/**
 * Устанавливаем токен для защиты
 * от межсайтовой подделки запроса
 */
function createCSRF(): void
{
    if (isset($_SESSION['_csrf']) !== true) {
        $hash = uniqid('');
        $_SESSION['_csrf'] = hash('sha512', time() . '' . $hash);
    }
}

/**
 * Проверяем подлинность токена csrf
 * если токен не совпадает, отправляем на 404 стр.
 */
function checkCSRF(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        if (!isset($_REQUEST['csrf_token'])) {
            $code = 404;
            //устанавливаем код ответа HTTP и подключаем шаблон ошибки по код
            dispatchNotFound($code);
        } elseif ($_REQUEST['csrf_token'] !== $_SESSION['_csrf']) {
            $code = 404;
            //устанавливаем код ответа HTTP и подключаем шаблон ошибки по код
            dispatchNotFound($code);
        }
    }
}

/**
 * уничтожаем токен csrf
 */
function destroyCSRF(): void
{
    //проверяем установлен ли токен
    if (!isset($_SESSION['_csrf'])) {
        //меняем значение токена
        $_SESSION['_csrf'] = 'destroy';
        //удаляем сессию токена
        unset($_SESSION['_csrf']);
    }
}

/**
 * подключение шаблона вида страницы
 * если шаблона нет, ошибка 404
 *
 * @param string $viewPath
 * @param array $data
 * @return string
 */
function render(string $viewPath, array $data = []): string
{
    //импортирует переменные из массива в текущую таблицу символов
    extract($data);
    //устанавливаем полный путь к виду страницы, для подключения
    $viewPath = __DIR__ . "/../views/{$viewPath}_tpl.php";
    //проверяем существование указанного файла или каталога
    //если нет подключаем 404 станицу
    if (!file_exists($viewPath)) {
        $code = 404;
        //устанавливаем код ответа HTTP
        http_response_code($code);
        //поключаем шаблон ошибки по коду
        require __DIR__ . "/../views/errors/{$code}.php";
        die;
    }
    //включаем буферизацию вывода
    ob_start();
    //подключаем шаблон вида
    include $viewPath;
    //получаем содержимое текущего буфера и удаляем его
    //то есть возвращаем шаблон ввиде строки, с уже вставленными переменными,
    //если они есть в шаблоне
    return ob_get_clean();
}

function redirect(string $http = ''): void
{
    if ($http) {
        $redirect = $http;
    } else {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
    }

    header("Location: {$redirect}");
    die;
}
