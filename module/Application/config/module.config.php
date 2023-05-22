<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Authentication\AuthenticationService;
use Laminas\Session\Storage\SessionArrayStorage;

$auth = new AuthenticationService();


		            
return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'api' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/admin',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'session' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/session',
                            'defaults' => [
                                'controller' => Controller\SessionController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'request' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/request',
                            'defaults' => [
                                'controller' => Controller\RequestController::class,
                                'action'     => 'index',
                            ],
                        ],
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
            Controller\AdminController::class => InvokableFactory::class,
            Controller\SessionController::class => InvokableFactory::class,
            Controller\RequestController::class => InvokableFactory::class,
            Controller\ApiController::class => InvokableFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Laminas\Session\Config\ConfigInterface' => 'Laminas\Session\Service\SessionConfigFactory',
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
            'application/admin/admin' => __DIR__ . '/../view/application/admin/admin.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/403' => __DIR__ . '/../view/error/403.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
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
                'label' => 'Admin',
                'route' => 'admin',
                'class' => 'nav-link',
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
    'session_config'  => [
        'remember_me_seconds' => 10,
        'name' => 'alantest1234',
        'use_cookies' => true,
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class,
        
    ],


   
];