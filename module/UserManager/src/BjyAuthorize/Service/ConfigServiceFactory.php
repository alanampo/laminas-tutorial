<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace BjyAuthorize\Service;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;
/**
 * Factory responsible of retrieving an array containing the BjyAuthorize configuration
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class ConfigServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return array
     */
    
     public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
     {
        $config = $serviceLocator->get('Config');   
        //return $config['bjyauthorize'];
         return new ConfigController(
             $config
         );
     }


}

//
