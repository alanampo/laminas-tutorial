<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Authentication\AuthenticationService;

$auth = new AuthenticationService();
		            
return [
    'router' => [
        'routes' => [
            // In module/Application/config/module.config.php:
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        // <-- change back here
                        'action' => 'index',
                    ],
                ],
            ],
            'application' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Home',
                'route' => 'home',
                'class' => 'nav-link',
            ],
            [
                'label' => 'Album',
                'route' => 'album',
                'class' => 'nav-link',
                'pages' => [
                    [
                        'label'  => 'Add',
                        'route'  => 'album',
                        'action' => 'add',
                    ],
                    [
                        'label'  => 'Edit',
                        'route'  => 'album',
                        'action' => 'edit',
                    ],
                    [
                        'label'  => 'Delete',
                        'route'  => 'album',
                        'action' => 'delete',
                    ],
                ],
            ],
            [
                'class' => 'nav-link',
                'label' => 'Blog',
                'route' => 'blog',
            ],
            [
                'class' => 'nav-link',
                'label' => $auth->hasIdentity() ? 'Logout' : 'Login',
                'route' => $auth->hasIdentity() ? 'logout' : 'login',
            ],
            !$auth->hasIdentity() ? [
                'class' => 'nav-link',
                'label' => 'SignUp',
                'route' => 'signup',
            ] : NULL
        ],
    ],
];