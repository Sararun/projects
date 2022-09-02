<?php

if (!empty($_POST['mode']) && ($_POST['mode'] === 'updated')) {
    $data = [];
    foreach ($_POST as $key => $value) {
        if (in_array($key, ['csrf_token', 'mode'])) {
            continue;
        }
        // Удаление html тэгов и сокращение строки
        $data[$key] = htmlspecialchars(strip_tags(trim($value)));
    }

    $errors = [];

    if (empty($data['title'])) {
        $errors[] = 'Fill in the name field';
    }

    if (mb_strlen($data['title']) > 150) {
        $errors[] = 'Title must be no more than 150 characters.';
    }

    if (empty($data['description'])) {
        $errors[] = 'Fill in the description field';
    }

    if (empty($data['description']) > 250) {
        $errors = 'Description must be no more than 250 characters.';
    }

    if (empty($data['deadline'])) {
        $errors[] = 'Set a due date for the task';
    }

    $id = $_GET['id'] ?? 0;

    if (!empty($errors)) {
        $_SESSION['any'] = $errors;
        $_SESSION['data'] = [
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
        ];
    } else {
        $query = "SELECT * FROM tasks WHERE id=:id LIMIT 1";
        $sth = $PDODriver->prepare($query);
        $sth->execute([
            ':id' => $id,
        ]);
        $item = $sth->fetch();

        if(empty($item)) {
            throw new \PDOException("page not found (#404)", 404);
        }

        $data['id'] = $id;
        $data['user_id'] = 1;
        $data['updated_at'] = date('Y-m-d H:i:s');

        $columns = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if ($key === 'id') {
                continue;
            }
            $columns[] = "{$key}=:{$key}";
            $params[":{$key}"] = $value;
        }

        try {
            $query = "UPDATE tasks SET"
                . implode(', ', $columns)
                . "WHERE id=:id LIMIT 1";
            $sth = $PDODriver->prepare($query);
            $sth->execute($params);
        } catch (\PDOException $e) {
            throw new PDOException("SQL: {$query}", 500);
        }

        if ($sth->rowCount() > 0) {
            $_SESSION['success'] = 'Success';
        } else {
            throw new \PDOException("page not found (#404)", 404);
        }
    }
}