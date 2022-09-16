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

    confirmDataEmail($user, $errors);
    confirmDataPassword($user,$errors );

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
