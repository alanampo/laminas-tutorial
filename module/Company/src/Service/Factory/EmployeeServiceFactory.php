<?php

namespace Company\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Company\Mapper\EmployeeMapperInterface;
use Company\Service\EmployeeService;

class EmployeeServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return EmployeeServiceFactory
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $employeeMapper = $container->get(EmployeeMapperInterface::class);
        return new EmployeeService($employeeMapper);
    }
}