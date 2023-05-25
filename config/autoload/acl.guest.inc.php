<?php

return [
    'routes' => [
        'home',
        'album',
        'blog',
        'login',
        'logout',
        'signup'
    ],
    'controllers' => [
        IndexController::class, //HOME
        AlbumController::class, 
        ListController::class, //BLOG
        LoginController::class,
        LogoutController::class,
        AuthController::class
    ]
    
]

?>