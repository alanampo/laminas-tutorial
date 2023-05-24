<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace UserManager;

use Laminas\EventManager\EventInterface;


use Laminas\ModuleManager\Feature\AutoloaderProviderInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\ControllerPluginProviderInterface;
use Laminas\ModuleManager\Feature\ViewHelperProviderInterface;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * BjyAuthorize Module
 *
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */
class Module implements
    AutoloaderProviderInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface,
    ControllerPluginProviderInterface,
    ViewHelperProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $event)
    {
        /* @var $app \Zend\Mvc\ApplicationInterface */
        $app            = $event->getTarget();
        /* @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
        $serviceManager = $app->getServiceManager();
        $config         = $serviceManager->get('BjyAuthorize\Config');
        $strategy       = $serviceManager->get($config['unauthorized_strategy']);
        $guards         = $serviceManager->get('BjyAuthorize\Guards');

        foreach ($guards as $guard) {
            $app->getEventManager()->attach($guard);
        }

        $app->getEventManager()->attach($strategy);
    }

    /**
     * {@inheritDoc}
     */

    //CHIEDERE SE SI PUÓ TOGLIERE QUESTO

    // public function getViewHelperConfig()
    // {
    //     return array(
    //         'factories' => array(
    //             'isAllowed' => function (AbstractPluginManager $pluginManager) {
                    
    //                 //DEPRECATO - DEVO FARE UNA FACTORY
    //                 $serviceLocator = $pluginManager->getServiceLocator();
    //                 /* @var $authorize \BjyAuthorize\Service\Authorize */
    //                 $authorize = $serviceLocator->get('BjyAuthorize\Service\Authorize');

    //                 return new \BjyAuthorize\View\Helper\IsAllowed($authorize);
    //             }
    //         ),
    //     );
    // }

    // /**
    //  * {@inheritDoc}
    //  */
    // public function getControllerPluginConfig()
    // {
    //     return array(
    //         'factories' => array(
    //             'isAllowed' => function (AbstractPluginManager $pluginManager) {
    //                 //DEPRECATO - DEVO FARE UNA FACTORY
    //                 $serviceLocator = $pluginManager->getServiceLocator();
                    
    //                 /* @var $authorize \BjyAuthorize\Service\Authorize */
    //                 $authorize = $serviceLocator->get('BjyAuthorize\Service\Authorize');

    //                 return new \BjyAuthorize\Controller\Plugin\IsAllowed($authorize);
    //             }
    //         ),
    //     );
    // }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
}
