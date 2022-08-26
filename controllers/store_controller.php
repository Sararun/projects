<?php
deleteCSRF();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [];
    foreach ($_POST as $key => $value) {
        if($key !== 'csrf_token') {
            $data[$key] = htmlspecialchars(strip_tags(trim($value)));

        }
    }

    $errors = [];

    if (empty($data['title'])) {
        $errors[] = 'Заполните поле название';
    }
    if (mb_strlen($data['title']) < 5){
        $errors[] = 'Слишком короткое название';
    }

    if (mb_strlen($data['title']) > 149) {
        $errors[] = 'Слишком длинное название';
    }

    if (empty($data['description'])) {
        $errors[] = 'Заполните поле описание';
    }

    if (mb_strlen($data['description']) < 5){
        $errors[] = 'Слишком короткое описание';
    }

    if (mb_strlen($data['description']) > 254) {
        $errors[] = 'Слишком длинное описание';
    }

    if (empty($data['deadline'])) {
        $errors[] = 'Заполните поле окончание задачи';
    }

    if (!empty($errors)) {
        $_SESSION['any'] = $errors;
    } else {
        $data['user_id'] = 1;
        $date = date('Y-m-d H:i:s');
        $data['created_at'] = $date;
        $data['updated_at'] = $date;

        $files = implode(', ', array_keys($data));
        $placeholders = str_repeat('?, ', count($data) - 1) . '?';
        $query = "INSERT INTO tasks ({$files}) VALUES ({$placeholders})";
        $sth = $dbh->prepare($query);
        $sth->execute(array_values($data));

        if ($sth->rowCount() > 0) {
            $_SESSION['success'] = 'Задание успешно добавлено';
        } else {
            $_SESSION['error'] = 'Ошибка сохранения';
        }
    }

    deleteCSRF();
    redirect();
}