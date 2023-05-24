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
            \BjyAuthorize\Controller\Plugin\IsAllowed::class =>  \BjyAuthorize\Factory\AuthorizeControllerFactory::class,
        ],
    ],

    'bjyauthorize' => array(
        // default role for unauthenticated users
        'default_role'          => 'guest',

        // default role for authenticated users (if using the
        // 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider' identity provider)
        'authenticated_role'    => 'user',

        // identity provider service name
        'identity_provider'     => 'BjyAuthorize\Provider\Identity\ZfcUserZendDb',

        // Role providers to be used to load all available roles into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'role_providers'        => array(),

        // Resource providers to be used to load all available resources into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'resource_providers'    => array(),

        // Rule providers to be used to load all available rules into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'rule_providers'        => array(),

        // Guard listeners to be attached to the application event manager
        'guards'                => array(),

        // strategy service name for the strategy listener to be used when permission-related errors are detected
        'unauthorized_strategy' => 'BjyAuthorize\View\UnauthorizedStrategy',

        // Template name for the unauthorized strategy
        'template'              => 'error/403',

        // cache options have to be compatible with Zend\Cache\StorageFactory::factory
        'cache_options'         => array(
            'adapter'   => array(
                'name' => 'memory',
            ),
            'plugins'   => array(
                'serializer',
            )
        ),

        // Key used by the cache for caching the acl
        'cache_key'             => 'bjyauthorize_acl'
    ),

    'service_manager' => array(
        'factories' => array(
            // 'BjyAuthorize\Cache'                    => 'BjyAuthorize\Service\CacheFactory',
            // 'BjyAuthorize\CacheKeyGenerator'        => 'BjyAuthorize\Service\CacheKeyGeneratorFactory',
            'BjyAuthorize\Config'                   => \BjyAuthorize\Service\ConfigServiceFactory::class,
            // 'BjyAuthorize\Guards'                   => 'BjyAuthorize\Service\GuardsServiceFactory',
            // 'BjyAuthorize\RoleProviders'            => 'BjyAuthorize\Service\RoleProvidersServiceFactory',
            // 'BjyAuthorize\ResourceProviders'        => 'BjyAuthorize\Service\ResourceProvidersServiceFactory',
            // 'BjyAuthorize\RuleProviders'            => 'BjyAuthorize\Service\RuleProvidersServiceFactory',
            // 'BjyAuthorize\Guard\Controller'         => 'BjyAuthorize\Service\ControllerGuardServiceFactory',
            // 'BjyAuthorize\Guard\Route'              => 'BjyAuthorize\Service\RouteGuardServiceFactory',
            // 'BjyAuthorize\Provider\Role\Config'     => 'BjyAuthorize\Service\ConfigRoleProviderServiceFactory',
            // 'BjyAuthorize\Provider\Role\ZendDb'     => 'BjyAuthorize\Service\ZendDbRoleProviderServiceFactory',
            // 'BjyAuthorize\Provider\Rule\Config'     => 'BjyAuthorize\Service\ConfigRuleProviderServiceFactory',
            // 'BjyAuthorize\Provider\Resource\Config' => 'BjyAuthorize\Service\ConfigResourceProviderServiceFactory',
            // 'BjyAuthorize\Service\Authorize'        => 'BjyAuthorize\Service\AuthorizeFactory',
            // 'BjyAuthorize\Provider\Identity\ProviderInterface'
            //     => 'BjyAuthorize\Service\IdentityProviderServiceFactory',
            // 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider'
            //     => 'BjyAuthorize\Service\AuthenticationIdentityProviderServiceFactory',
            // 'BjyAuthorize\Provider\Role\ObjectRepositoryProvider'
            //     => 'BjyAuthorize\Service\ObjectRepositoryRoleProviderFactory',
            // 'BjyAuthorize\Collector\RoleCollector'  => 'BjyAuthorize\Service\RoleCollectorServiceFactory',
            // 'BjyAuthorize\Provider\Identity\ZfcUserZendDb'
            //     => 'BjyAuthorize\Service\ZfcUserZendDbIdentityProviderServiceFactory',
            // 'BjyAuthorize\View\UnauthorizedStrategy'
            //     => 'BjyAuthorize\Service\UnauthorizedStrategyServiceFactory',
            // 'BjyAuthorize\Service\RoleDbTableGateway' => 'BjyAuthorize\Service\UserRoleServiceFactory',
        ),
        // 'invokables'  => array(
        //     'BjyAuthorize\View\RedirectionStrategy' => 'BjyAuthorize\View\RedirectionStrategy',
        // ),
        // 'aliases'     => array(
        //     'bjyauthorize_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
        // ),
        // 'initializers' => array(
        //     'BjyAuthorize\Service\AuthorizeAwareServiceInitializer'
        //         => 'BjyAuthorize\Service\AuthorizeAwareServiceInitializer'
        // ),
    ),



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
