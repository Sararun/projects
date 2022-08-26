<?php
declare(strict_types = 1);
error_reporting(-1);
session_start();

$url = trim($_SERVER['REQUEST_URI'], '/');
$urlParts = parse_url($url);

require __DIR__ . "/models/app_model.php";

createCSRF();
checkCSRF();

$routes = require __DIR__ . '/config/routes.php';

$controller = router($urlParts['path'], $routes);

if (is_null($controller)) {
    pageNotFound();
}

$config = require __DIR__ . '/config/db.php';
$dbh  = connectionDB($config);

require __DIR__ . "/controllers/{$controller}_controller.php";
require __DIR__ . 'views/layouts/default.php';