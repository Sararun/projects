<?php

function dump($data): void
{
    echo '<pre>'; var_dump($data); echo '</pre>';
}

function connectionDB(array $config): PDO
{
    static $pdo = null;

    if (!is_null($pdo)) {
        return $pdo;
    }

    try {
        $pdo = new \PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
            $config['username'],
            $config['password'],
            $config['options']
        );

        return $pdo;
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

function render(string $path, array $data = []): string
{
    extract($data);

    $viewPath = __DIR__ . "/../views/todo-list/{$path}_tpl.php";

    if (!file_exists($viewPath)) {
        pageNotFound();
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
            pageNotFound();
        }
    }
}

function deleteCSRF(): void
{
    unset($_SESSION['_csrf']);
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