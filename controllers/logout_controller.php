<?php
/** @var $PDODriver */

//Выход из профиля
$_SESSION['user'] = [];
unset($_SESSION['user']);
redirect('/');