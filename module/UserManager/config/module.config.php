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
    	],
    ],
    'controllers' => [
    	'factories' => [
    		Controller\AuthController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
    	'template_map' => [

    		'auth/create'   => __DIR__ . '/../view/user-manager/auth/create.phtml',
           
    	],
    	'template_path_stack' => [
    		'user-manager' => __DIR__ . '/../view'
    	]
    ]
];
