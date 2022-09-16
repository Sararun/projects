<?php
/** @var $PDODriver */

if (!empty($_GET['id'])) {
    $id = $_GET['id'] ?? 0;
    $where = 'WHERE id=:id';
    $params = [':id' => $id];

    selectTasks($params, $where);

    $query = "DELETE FROM tasks WHERE id=:id LIMIT 1";
    $sth = $PDODriver->prepare($query);
    $sth->execute($params);

    if ($sth->rowCount() > 0) {
        $_SESSION['success'] = 'Успешно удалено.';
    } else {
        $_SESSION['error'] = 'Ошибка удаления.';
    }

    redirect();
} else {
    throw new \PDOException("Page not found (#404) ", 404);
}
