<?php

namespace Company\Controller\Factory;

use Company\Controller\EmployeeController;
use Company\Service\EmployeeServiceInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class EmployeeControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return EmployeeController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        //get service
        $employeeService = $container->get(EmployeeServiceInterface::class);
        
        return new EmployeeController($employeeService);
    }
}