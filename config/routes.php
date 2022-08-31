<?php

return [
    [
        'url' => '#^$|^\?#',
        'controller' => 'index',
    ],
    [
        'url' => '#^api/update-status?#',
        'controller' => 'api/update_status',
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
];
