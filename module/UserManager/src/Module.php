<?php

declare(strict_types=1);

namespace UserManager;

use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\AbstractPluginManager;
use UserManager\Model\Table\UsersTable;


use Laminas\EventManager\EventInterface;
use Laminas\Mvc\MvcEvent;

class Module 
{
    public function onBootstrap(MvcEvent $event)
    {
        /* @var $app \Zend\Mvc\ApplicationInterface */
        $app            = $event->getTarget();
        /* @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
        
        $serviceManager = $app->getServiceManager();
        
        
        
        $config         = $serviceManager->get('BjyAuthorize\Config');
        print_r($config);
        die();
        
        $configBjy = $config["service_manager"]["factories"]["BjyAuthorize\Config"];
       
        $configGuard = $serviceManager->get('config')["service_manager"]["factories"]["BjyAuthorize\Guards"];
        //$configGuard = $config["service_manager"]["factories"]["BjyAuthorize\Guards"];
        // $strategy       = $serviceManager->get($config['unauthorized_strategy']);
        
       
        // foreach ($guards as $guard) {
            
        //     $app->getEventManager()->attach($guard);
        // }

        // $app->getEventManager()->attach($strategy);
    }


    public function getConfig(): array
    {
        
        return include __DIR__ . "/../config/module.config.php";
    }

    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                UsersTable::class => function($sm){
                    $dbAdapter = $sm->get(Adapter::class);
                    return new UsersTable($dbAdapter);
                }
            ]
        ];
    }

    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'isAllowed' => function ($container) {
                    $authorize = $container->get('BjyAuthorize\Service\Authorize');
                    return new \BjyAuthorize\Controller\Plugin\IsAllowed($authorize);
                }
            ),
        );
    }
}