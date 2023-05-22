<?php

declare(strict_types=1);

namespace Application\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;

use Laminas\Log\Logger;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\AdminController;


class AdminControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$logger = $container->get('LaminasLog');

        return new AdminController(
			$logger
		);
	}
}
