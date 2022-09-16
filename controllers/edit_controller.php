<?php
/** @var  $PDODriver */
/** @var $currentController */

$id = $_GET['id'] ?? 0;

$where = 'WHERE id=:id';
$params = [':id' => $id];

//Если user не пустой или user обычный, то присваиваем id
if (!empty($_SESSION['user']) && $_SESSION['user']['role'] == 2) {
    $userId = $_SESSION['user']['id'];
    $params[':user_id'] = $userId;
    $where .= " AND user_id=:user_id";
}

selectTasks($params, $where);


role1();

$content = render("/tasks/{$currentController}", [
    'item' => $item,
    'users' => $users ?? [],
]);
