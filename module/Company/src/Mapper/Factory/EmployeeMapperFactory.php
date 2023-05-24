<?php
namespace Company\Mapper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Company\Model\Employee;
use Company\Mapper\EmployeeMapper;

class EmployeeMapperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new EmployeeMapper(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Employee()
            );
    }
}