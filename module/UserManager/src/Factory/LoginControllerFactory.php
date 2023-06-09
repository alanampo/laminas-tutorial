<?php

declare(strict_types=1);

namespace UserManager\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use UserManager\Controller\LoginController;
use UserManager\Model\Table\UsersTable;


class LoginControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new LoginController(
			$container->get(Adapter::class),
			$container->get(UsersTable::class)
		);
	}
}
