<?php
/** @var $PDODriver */
/** @var $controller */

if (!empty($_GET['out']) && ($_GET['out'] == 1)) {
    $_SESSION['user'] = [];
    unset($_SESSION['user']);
    redirect();
}
