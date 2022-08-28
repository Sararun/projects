<?php

return [
    [
        'url' => '#^$|^\?#',
        'controller' => 'index',
    ],
    [
        'url' => '#^api/update-status?#',
        'controller' => 'Api/update_status',
    ]
];
