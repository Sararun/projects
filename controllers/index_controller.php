<?php
/** @var $PDODriver */
/** @var $controller */

$where = ' WHERE 1';
$params = [];

if (!empty($_SESSION['user']) && $_SESSION['user']['role'] == 2) {
    $userId = $_SESSION['user']['id'];
    $params[':user_id'] = $userId;
    $where .= " AND t.user_id=:user_id";
}

$search = $_GET['search'] ?? null;
/*
 * Если строка поиска не пустая и больше 3 символов,
 то отправляем запрос в базу
 */
if (!empty($search) && (mb_strlen($search) >= 3)) {
    $search = htmlspecialchars(strip_tags(trim($search)));
    $search = "%{$search}%";
    $where .= " AND t.title LIKE '{$search}' OR t.description LIKE '{$search}' ";
} elseif (!empty($search)) {
    $_SESSION['error'] = 'Слишком короткий запрос';
}

$filter = $_GET['filter'] ?? null;
//Если в поле фильтр что-то есть
if (!empty($filter) && ($filter == 1)) {
    $filterData = [];
    foreach ($_GET as $key => $value) {
        $filterData[$key] = htmlspecialchars(strip_tags(trim($value)));
    }
    /*
     * Дальше идёт код сравнивающий с
     * фильтром и select в базу c существующим параметром
     */
    if (!empty($filterData['username'])) {
        $title = "%{$filterData['username']}%";
        $where .= " AND u.username LIKE '{$title}'";
    }

    if (!empty($filterData['username'])) {
        $title = "%{$filterData['username']}%";
        $where .= " AND u.username LIKE '{$title}'";
    }

    if (!empty($filterData['title'])) {
        $title = "%{$filterData['description']}%";
        $where .= " AND t.description LIKE '{$description}'";
    }

    if (!empty($filterData['executed'])) {
        $executed = ($filterData['executed'] == 1) ? 1 : 0;
        $executed = "%{$executed}%";
        $where .= "AND t.executed LIKE '{$executed}'";
    }

    if (!empty($filterData['date_from'])) {
        $dateFrom = date('Y-m-d', strtotime($filterData['date_from']));
        $where .= " AND DATE(t.created_at)>='$dateFrom'";
    }

    if (!empty($filterData['date_to'])) {
        $dateTo = date('Y-m-d', strtotime($filterData['date_to']));
        $where .= " AND DATE(t,deadline)<='{$dateTo}'";
    }
}


//строка sql запроса, для получения всех записей задания
$query = "SELECT * FROM tasks ORDER BY deadline DESC";
//подготавливаем запрос к выполнению
//и возвращаем связанный с этим запросом объект
$sth = $PDODriver->prepare($query);
//запускаем подготовленный запрос на выполнение
$sth->execute();
//возвращает массив, содержащий все записи в бд
$taskList = $sth->fetchAll();
//подключаем рендер и передаем массив
//записей в подключаемый вид для подстановке в шаблоне
$content = render($controller, [
    'taskList' => $taskList,
]);
