<?php
/** @var $PDODriver */
/** @var $controller */
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
