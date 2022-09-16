<?php
/** @var $PDODriver */
/** @var $currentController */

$whereQuery = $whereCount = ' WHERE 1';
$paramsQuery = [];

if ($_SESSION['user']['role'] == 2) {
    $userId = $_SESSION['user']['id'];
    $paramsQuery[':user_id'] = $userId;
    $whereQuery .= " AND t.user_id=:user_id";
}

$search = $_GET['search'] ?? null;
/*
 * Если строка поиска не пустая и больше 3 символов,
 то отправляем запрос в базу
 */
if (!empty($search) && (mb_strlen($search) >= 3)) {
    $search = htmlspecialchars(strip_tags(trim($search)));
    $search = "%{$search}%";
    $whereQuery .= " AND t.title LIKE '{$search}' OR t.description LIKE '{$search}' ";
} elseif (isset($_GET['search_value']) && $_GET['search_value'] == 1) {
    $_SESSION['error'] = 'Введите поисковой запрос';
}

$filter = $_GET['filter'] ?? null;
$builderQuery = builderQueryData($filter);

$whereQuery .= $builderQuery . " ORDER BY t.deadline DESC";

$page = $_GET['page'] ?? 1;//Кол-во страниц на выход
$perPage = 5;

$totalPage = getTasksCount($paramsQuery, $whereQuery);//Кол-во всех записей
$countPages = ceil($totalPage / $perPage) ?:1; //Округление дроби в большую сторону

if ($page > $countPages) {
    $page = $countPages;
}
//вычитаем -1 так как берём с 0
$limit = ($page - 1) * $perPage;
$offset = $perPage;

$whereQuery .= " LIMIT {$limit}, {$offset}";

$paginator = paginator($page, $countPages);
$taskList = getAllTasks($whereQuery, $paramsQuery);

if ($_SESSION['user']['role'] == 1) {
    $query = "SELECT id, username FROM users ORDER BY id DESC";
    $sth = $PDODriver->prepare($query);
    $sth->execute();
    $users = $sth->fetchAll();
}

//подключаем рендер и передаем массив
//записей в подключаемый вид для подстановке в шаблоне
$content = render("/tasks/{$currentController}", [
    'taskList' => $taskList,
    'users' =>  $users ?? [],
    'paginator' => $paginator,
]);
