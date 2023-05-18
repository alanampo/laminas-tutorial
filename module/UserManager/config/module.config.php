<?php

declare(strict_types=1);

namespace UserManager;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
    	'routes' => [
    		'signup' => [
    			'type' => Literal::class,
    			'options' => [
    				'route' => '/signup',
    				'defaults' => [
    					'controller' => Controller\AuthController::class,
    					'action' => 'create'
    				],
    			],
    		],
			'login' => [
                'type' => Segment::class, # change route type from Literal to Segment
                'options' => [
                    'route' => '/login[/:returnUrl]',
                    'constraints' => [
                        'returnUrl' => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action' => 'index'
                    ],
                ],
            ],
			'logout' => [
                'type' => Literal::class, # change route type from Literal to Segment
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\LogoutController::class,
                        'action' => 'index'
                    ],
                ],
            ],
    	],
    ],
    'controllers' => [
    	'factories' => [
    		Controller\AuthController::class => Factory\AuthControllerFactory::class,
			Controller\LoginController::class => Factory\LoginControllerFactory::class,
			Controller\LogoutController::class => InvokableFactory::class,
        ],
    ],
    /* 'view_manager' => [
    	'template_map' => [

    		'auth/create'   => __DIR__ . '/../view/user-manager/auth/create.phtml',
			'login/index'   => __DIR__ . '/../view/user-manager/auth/login.phtml', 
           
    	],
    	'template_path_stack' => [
    		'user-manager' => __DIR__ . '/../view'
    	]
    ] */
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    
];
