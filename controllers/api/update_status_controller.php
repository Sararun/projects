<?php

//проверяем откуда пришел запрос
if ($_POST['mode'] === 'update_status') {
    //очищаем данные от тегов, html и тд
    $id = htmlspecialchars(strip_tags(trim($_POST['id'])));
    $value = htmlspecialchars(strip_tags(trim($_POST['value'])));
    //создаем массив с ошибками
    $response = [
        'error' => true,
        'value' => $value,
    ];
    //проверяем, если больше 0, тогда выполняем код
    if ($id > 0) {
        //устанавливаем значение для подстановки в input,
        //если пришло 1, устанавливаем 0, и на оборот
        $executed = ($value == 1) ? 0 : 1;

        //строка запроса sql
        $query = "UPDATE tasks SET 
        executed=:executed,
        lead_time=:lead_time
        WHERE id=:id LIMIT 1";
        //подготавливаем запрос к выполнению
        //и возвращаем связанный с этим запросом объект
        $sth = $PDODriver->prepare($query);

        $leadTime = ($executed)
            ? date('Y-m-d H:i:s')
            : null;

        //запускаем подготовленный запрос на выполнение
        $sth->execute([
            ':id' => $id,
            ':executed' => $executed,
            ':lead_time' => $leadTime,
        ]);
        //проверяем кол-во строк затронутых последим запросом
        //если больше 1, в нашем случае 1, тогда создаем массив
        //с новым значением для подстановки в input
        if ($sth->rowCount() > 0) {
            $response = [
                'error' => false,
                'value' => $executed,
                'lead_time' => $leadTime,
            ];
        }
    }

    die (json_encode($response, JSON_UNESCAPED_UNICODE));
}
