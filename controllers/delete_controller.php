<?php
/** @var $PDODriver */

if (!empty($_GET['id'])) {
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

    $query = "DELETE FROM tasks WHERE id=:id LIMIT 1";
    $sth = $PDODriver->prepare($query);
    $sth->execute([
        ':id' => $id,
    ]);

    if ($sth->rowCount() > 0) {
        $_SESSION['success'] = 'Успешно удалено.';
    } else {
        $_SESSION['error'] = 'Ошибка удаления.';
    }

    redirect();
} else {
    throw new \PDOException("Page not found (#404) ", 404);
}
