<?php

function dump($data): void
{
    echo '<pre>'; var_dump($data); echo '</pre>';
}

function connectionDB(array $config): PDO
{
    static $dbh = null;

    if (!is_null($dbh)) {
        return $dbh;
    }

    try {
        $dbh = new \PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
            $config['username'],
            $config['password'],
            $config['options']
        );

        return $dbh;
    } catch (\PDOException $e) {
        die ('Error server ' . $e->getMessage());
    }
}

function router(string $path, array $routes): ?string
{
    foreach ($routes as $route) {
        if (preg_match($route['url'], $path)) {
            return $route['controller'];
        }
    }

    return null;
}

function pageNotFound(): void
{
    http_response_code(404);
    require __DIR__ . '/../views/errors/404.php';
    die;
}

function render(string $viewPath, array $data = []): string
{
    extract($data);

    $viewPath = __DIR__ . "/../views/tasks/{$viewPath}_tpl.php";

    if (!file_exists($viewPath)) {
        $code = 404;
        http_response_code($code);
        require __DIR__ . "/../views/errors/{$code}.php";
        die;
    }

    ob_start();
    include $viewPath;

    return ob_get_clean();
}

function createCSRF(): void
{
    if (isset($_SESSION['_csrf']) !== true) {
        $hash = uniqid('');
        $_SESSION['_csrf'] = hash('sha512', time() . '' . $hash);
    }
}

function checkCSRF(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        if (!isset($_REQUEST['csrf_token']) && ($_REQUEST['csrf_token'] !== $_SESSION['_csrf'])) {
            $code = 404;
            http_response_code($code);
            require __DIR__ . "/../Views/errors/{$code}.php";
            die;
        }
    }
}

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
