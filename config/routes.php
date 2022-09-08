<?php
//Пути выводимые в uri
return [
    [
        'url' => '#^$|^\?#',
        'controller' => 'index',
    ],
    [
        'url' => '#^api/update-status?#',
        'controller' => 'Api/update_status',
    ],
    [
        'url' => '#^store?#',
        'controller' => 'store',
    ],
    [
        'url' => '#^edit?#',
        'controller' => 'edit',
    ],
    [
        'url' => '#^update?#',
        'controller' => 'update',
    ],
    [
        'url' => '#^delete?#',
        'controller' => 'delete',
    ],
    [
        'url' => '#^register?#',
        'controller' => 'register',
    ],
    [
        'url' => '#^login?#',
        'controller' => 'login',
    ],
    [
        'url' => '#^logout?#',
        'controller' => 'logout',
    ],
    [
        'url' => '#^success_register?#',
        'controller' => 'success_register',
    ],
];
