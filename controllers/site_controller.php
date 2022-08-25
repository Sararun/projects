<?php

$query = "SELECT * FROM tasks ORDER BY deadline DESC";
$sth = $dbh->prepare($query);
$sth->execute();

$taskList = $sth->fetchAll();

$content = render($controller, [
    'taskList' => $taskList,
]);