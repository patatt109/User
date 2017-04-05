<?php

return [
    [
        'route' => '/login',
        'target' => [\Modules\User\Controllers\AuthController::class, 'login'],
        'name' => 'login'
    ],
    [
        'route' => '/register',
        'target' => [\Modules\User\Controllers\AuthController::class, 'register'],
        'name' => 'register'
    ],
    [
        'route' => '/logout',
        'target' => [\Modules\User\Controllers\AuthController::class, 'logout'],
        'name' => 'logout'
    ]
];