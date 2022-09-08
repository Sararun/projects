<?php
//Подключение бд
return [
    'host' => 'localhost',
    'dbname' => 'db-vlad-projects',
    'username' => 'root',
    'password' => 'root',
    'options' => [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
    ],
];
