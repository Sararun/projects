<?php
/** @var $PDODriver */

if ($_POST['mode'] === 'create') {

    $data = [];
    foreach ($_POST as $key => $value) {
        if (in_array($key, ['csrf_token', 'mode'])) {
            continue;
        }
        $data[$key] = htmlspecialchars(strip_tags(trim($value)));
    }

    $errors = [];

    validFields($data, $errors);

    if (!empty($errors)) {
        $_SESSION['any'] = $errors;
        $_SESSION['data'] = [
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
        ];
    } else {
        $date = date('Y-m-d H:i:s');
        $data['created_at'] = $date;
        $data['updated_at'] = $date;
        $data['user_id'] = $data['user_id'] ?? $_SESSION['user']['id'];

        $fields = implode(', ', array_keys($data));
        $placeholders = str_repeat('?, ', count($data) - 1) . '?';
        $query = "INSERT INTO tasks ({$fields}) VALUES ({$placeholders})";
        $sth = $PDODriver->prepare($query);
        $sth->execute(array_values($data));

        $lastId = $PDODriver->lastInsertId();

        if (!empty($lastId)) {
            $_SESSION['success'] = 'Успешно сохранено.';
        }
    }

    redirect();
}

