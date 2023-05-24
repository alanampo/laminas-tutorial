<?php

declare(strict_types=1);

namespace BjyAuthorize\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use \BjyAuthorize\Controller\Plugin\IsAllowed;
class AuthorizeControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
        $authorize = $container->get('BjyAuthorize\Service\Authorize');

		return new IsAllowed(
			$authorize
		);
	}
}
