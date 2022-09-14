<?php
/** @var $PDODriver */

if (!empty($_POST['mode']) && ($_POST['mode'] === 'create')) {
    $_POST['mode'] = 'create';
    $data = [];
    foreach ($_POST as $key => $value) {
        if (in_array($key, ['csrf_token', 'mode'])) {
            continue;
        }
        $data[$key] = htmlspecialchars(strip_tags(trim($value)));
    }

    $errors = [];

    if (empty($data['title'])) {
        $errors[] = 'Fill in the name field.';
    }

    if (mb_strlen($data['title']) > 150) {
        $errors[] = 'Title must be no more than 150 characters.';
    }

    if (empty($data['description'])) {
        $errors[] = 'Fill in the description field.';
    }

    if (mb_strlen($data['description']) > 250) {
        $errors[] = 'Description must be no more than 250 characters.';
    }

    if (empty($data['deadline'])) {
        $errors[] = 'Set a due date for the task.';
    }

    if (!empty($errors)) {
        $_SESSION['any'] = $errors;
        $_SESSION['data'] = [
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
        ];
    } else {
        $data['user_id'] = $data['user_id'] ?? $_SESSION['user']['id'];
        $date = date('Y-m-d H:i:s');
        $data['created_at'] = $date;
        $data['updated_at'] = $date;

        $fields = implode(', ', array_keys($data));
        $placeholders = str_repeat('?, ', count($data) - 1) . '?';
        $query = "INSERT INTO tasks ({$fields}) VALUES ({$placeholders})";
        $sth = $PDODriver->prepare($query);
        $sth->execute(array_values($data));

        $lastId = $PDODriver->lastInsertId();

        if (!empty($lastId)) {
            $_SESSION['success'] = 'Success';
        }
    }

    redirect();
}

