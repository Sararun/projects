<?php
/** @var $PDODriver */
/** @var $currentController */

if (!empty($_POST['mode']) && ($_POST['mode'] === 'register')) {
    $user = [];
    foreach ($_POST as $key => $value) {
        if ($key === 'csrf_token' || $key === 'mode') {
            continue;
        }

        $user[$key] = htmlspecialchars(strip_tags(trim($value)));
    }

    $errors = [];

    if (empty($user['username'])) {
        $errors['empty_name'] = 'Заполните поле имя' . PHP_EOL;
    } elseif (preg_match("#[^а-яёa-z]#ui", $user['username'])) {
        $errors['symbol_name'] = 'Имя содержит недопустимые символы' . PHP_EOL;
    } elseif (mb_strlen($user['username']) < 3) {
        $errors['length_name'] = 'Имя содержит менее 3 символов' . PHP_EOL;
    }

    if (empty($user['email'])) {
        $errors['empty_email'] = 'Заполните поле email' . PHP_EOL;
    } elseif (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['error_email'] = 'Некорректный email' . PHP_EOL;
    }

    if (empty($user['password'])) {
        $errors['empty_password'] = 'Заполните поле пароль' . PHP_EOL;
    } elseif (preg_match("#^\d+$#", $user['password'])) {
        $errors['number_password'] = 'Пароль не должен содержать только цифры' . PHP_EOL;
    } elseif (preg_match("#[^a-z0-9]#ui", $user['password'])) {
        $errors['symbol_password'] = 'Пароль содержит недопустимые символы' . PHP_EOL;
    } elseif (strlen($user['password']) <= 5) {
        $errors['length_password'] = 'Пароль содержит менее 5 символов' . PHP_EOL;
    }

    if (empty($user['password_confirm'])) {
        $errors['empty_password_confirm'] = 'Заполните поле подтверждение пароля' . PHP_EOL;
    }

    if ($user['password'] !== $user['password_confirm']) {
        $errors['length_password'] = 'Пароли не совпадают' . PHP_EOL;
    }

    $redirect = 'register';

    if (!empty($errors)) {
        $_SESSION['any'] = $errors;
    } else {
        $query = "SELECT id FROM users WHERE email=:email LIMIT 1";
        $sth = $PDODriver->prepare($query);
        $sth->execute([
            ':email' => $user['email'],
        ]);
        if ($sth->rowCount() > 0) {
            $_SESSION['error'] = 'Пользователь с такие email уже зарегистрирован.';
        } else {
            unset($user['password_confirm']);

            $date = date('Y-m-d H:i:s');
            $user['created_at'] = $date;
            $user['updated_at'] = $date;
            $password = $user['password'];
            $user['password'] = password_hash($password, PASSWORD_DEFAULT);

            $fields = implode(', ', array_keys($user));
            $placeholders = str_repeat('?, ', count($user) - 1) . '?';
            $query = "INSERT INTO users ({$fields}) VALUES ({$placeholders})";
            $sth = $PDODriver->prepare($query);
            $sth->execute(array_values($user));

            $lastId = $PDODriver->lastInsertId();

            if (!empty($lastId)) {
                $_SESSION['success_register'] = [
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'password' => $password,
                ];
                $redirect = '/success_register';
            } else {
                $_SESSION['error'] = 'Ошибка регистрации';
            }
        }
    }

    redirect($redirect);
}

//подключаем рендер и передаем массив
//записей в подключаемый вид для подстановке в шаблоне
$content = render("/auth/{$currentController}");
