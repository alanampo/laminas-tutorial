<?php

declare(strict_types=1);

namespace UserManager;

use Laminas\Db\Adapter\Adapter;
use UserManager\Model\Table\UsersTable;

class Module
{
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