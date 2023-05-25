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