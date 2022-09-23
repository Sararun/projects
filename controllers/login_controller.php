<?php
/** @var $PDODriver */
/** @var $currentController */

if (!empty($_POST['mode']) && ($_POST['mode'] === 'login')) {
    $user = [];
    foreach ($_POST as $key => $value) {
        if ($key === 'csrf_token' || $key === 'mode') {
            continue;
        }

        $user[$key] = htmlspecialchars(strip_tags(trim($value)));
    }

    $errors = [];
    confirmDataEmail($user, $errors);
    confirmDataPassword($user,$errors );

    $redirect = 'login';

    //Проверка полей на уровне входа в профиль
    if (!empty($errors)) {
        $_SESSION['any'] = $errors;
    } else {
        selectIdUsers($user);

        if ($sth->rowCount() === 0) {
            $_SESSION['error'] = 'Email/Пароль введены не верно.';
        } else {
            $userData = $sth->fetch();
            if (!password_verify($user['password'], $userData['password'])) {
                $_SESSION['error'] = 'Email/Пароль введены не верно.';
            } else {
                unset($userData['password']);
                $_SESSION['user'] = $userData;
                $redirect = '/';
            }
        }
    }

    redirect($redirect);
}

//подключаем рендер и передаем массив
//записей в подключаемый вид для подстановки в шаблоне
$content = render("/auth/{$currentController}");
