<?php

namespace Company;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    // This lines opens the configuration for the RouteManager
    'router'          => [
        'routes' => [
            'employee' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/employee',
                    'defaults' => [
                        'controller' => Controller\EmployeeController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'index' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/:id',
                            'defaults' => [
                                'action' => 'index',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
            // Update this line:
            // Model\PostRepositoryInterface::class => Model\LaminasDbSqlRepository::class,
            // Model\PostCommandInterface::class => Model\LaminasDbSqlCommand::class,
        ],
        'factories' => [
            Service\EmployeeServiceInterface::class => Service\Factory\EmployeeServiceFactory::class,       
            Mapper\EmployeeMapperInterface::class => Mapper\Factory\EmployeeMapperFactory::class,       
            // Model\PostRepository::class => InvokableFactory::class,
            // Model\LaminasDbSqlRepository::class => Factory\LaminasDbSqlRepositoryFactory::class,
            // Model\PostCommand::class => InvokableFactory::class,
            // Model\LaminasDbSqlCommand::class => Factory\LaminasDbSqlCommandFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\EmployeeController::class => Controller\Factory\EmployeeControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];