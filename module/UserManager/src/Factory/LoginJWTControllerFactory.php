<?php

namespace UserManager\Factory;

use Laminas\Authentication\AuthenticationService;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;


class LoginJWTControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $authenticationService = $container->get(AuthenticationService::class);

        return new $requestedName(
            $authenticationService
        );
    }
}
