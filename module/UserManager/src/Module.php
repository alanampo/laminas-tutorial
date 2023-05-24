<?php

declare(strict_types=1);

namespace UserManager;

use Laminas\Db\Adapter\Adapter;
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
        
        
        //$config         = $serviceManager->get('BjyAuthorize\Config');
        // $strategy       = $serviceManager->get($config['unauthorized_strategy']);
        // $guards         = $serviceManager->get('BjyAuthorize\Guards');

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
}