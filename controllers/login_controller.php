<?php
/** @var $PDODriver */
/** @var $controller */

if (!empty($_POST['mode']) && ($_POST['mode'] === 'login')) {
    $user = [];
    foreach ($_POST as $key => $value) {
        if ($key === 'csrf_token' || $key === 'mode') {
            continue;
        }

        $user[$key] = htmlspecialchars(strip_tags(trim($value)));
    }

    $errors = [];
    //Валидация мейла
    if (empty($user['email'])) {
        $errors['empty_email'] = 'Заполните поле email' . PHP_EOL;
    } elseif (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['error_email'] = 'Некорректный email' . PHP_EOL;
    }

    //Валидация пароля
    if (empty($user['password'])) {
        $errors['empty_password'] = 'Заполните поле пароль' . PHP_EOL;
    } elseif (preg_match("#^\d+$#", $user['password'])) {
        $errors['number_password'] = 'Пароль не должен содержать только цифры' . PHP_EOL;
    } elseif (preg_match("#[^a-z0-9]#ui", $user['password'])) {
        $errors['symbol_password'] = 'Пароль содержит недопустимые символы' . PHP_EOL;
    } elseif (strlen($user['password']) <= 5) {
        $errors['length_password'] = 'Пароль содержит менее 5 символов' . PHP_EOL;
    }

    $redirect = 'login';

    //Проверка полей на уровне входа в профиль
    if (!empty($errors)) {
        $_SESSION['any'] = $errors;
    } else {
        $query = "SELECT * FROM users WHERE email=:email LIMIT 1";
        $sth = $PDODriver->prepare($query);
        $sth->execute([
            ':email' => $user['email'],
        ]);

        if ($sth->rowCount() === 0) {
            $_SESSION['error'] = 'Email/Пароль введены не верно.';
        } else {
            $userData = $sth->fetch();
            if (!password_verify($user['password'], $userData['password'])) {
                $_SESSION['error'] = 'Email/Пароль введены не верно.';
            }
            {
                unset($userData['password']);
                $_SESSION['user'] = $userData;
                $redirect = '/';
            }
        }
    }

    redirect($redirect);
}

//подключаем рендер и передаем массив
//записей в подключаемый вид для подстановке в шаблоне
$content = render("/auth/{$controller}");
