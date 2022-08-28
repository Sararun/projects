<?php

/**
 * Тут можно упростить, так как если Post['mode']
 * сравнивается с update_status,
 * то это уже означает, что не пустой. Но не факт,
 * скорее всего мы тут сравниваем просто тип данных
 */
if (!empty($_POST['mode']) && ($_POST['mode'] === 'update_status')) {
    $id = htmlspecialchars(strip_tags(trim($_POST['id'])));
    $value = htmlspecialchars((strip_tags(trim($_POST['value']))));

    $response = [
        'error' => true,
        'value' => $value,
    ];

    if ($id > 0) {
        $executed = ($value == 1) ? 0 : 1;

        $query = "UPDATE tasks SET execueted=:execueted WHERE id=:id LIMIT 1";

        $sth = $PDODriver->prepare($query);
        $sth->execute([
            ':id' => $id,
            ':executed' => $executed,
        ]);

        if ($sth->rowCount() > 0) {
            $response = [
                'error' => false,
                'value' => $executed,
            ];
        }
    }
    die (json_encode($response, JSON_UNESCAPED_UNICODE));
}