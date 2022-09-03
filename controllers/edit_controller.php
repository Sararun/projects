<?php
/** @var  $PDODriver */
/** @var $controller */
$id = $_GET['id'] ?? 0;

$query = "SELECT * FROM `tasks` WHERE id=:id LIMIT 1";
$sth = $PDODriver->prepare($query);
$sth->execute([
    ':id' => $id,
]);
$item = $sth->fetch();

if (empty($item)) {
    throw new \PDOException("Page not found (#404) ", 404);
}

$content = render($controller, [
    'item' => $item,
]);
