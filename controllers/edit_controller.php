<?php
/** @var  $PDODriver */
/** @var $currentController */

$taskId = $_GET['id'] ?? 0;

$where = 'WHERE id=:id';
$params = [':id' => $taskId];

//Если user не пустой или user обычный, то присваиваем id
if (!empty($_SESSION['user']) && $_SESSION['user']['role'] == 2) {
    $userId = $_SESSION['user']['id'];
    $params[':user_id'] = $userId;
    $where .= "AND user_id=:user_id";
}

$query = "SELECT * FROM `tasks` {$where} LIMIT 1";
$sth = $PDODriver->prepare($query);
$sth->execute($params);
$item = $sth->fetch();

if (empty($item)) {
    throw new \PDOException("Page not found (#404) ", 404);
}

//Если user не пустой или это админ, то вывод пользователей
if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] == 1)) {
    $query = "SELECT id, username FROM users ORDER BY id DESC";
    $sth = $PDODriver->prepare($query);
    $sth->execute();
    $users = $sth->fetchAll();
}

$content = render("/tasks/{$currentController}", [
    'item' => $item,
    'users' => $users ?? [],
]);
