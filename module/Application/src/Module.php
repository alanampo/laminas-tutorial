<?php

declare(strict_types=1);

namespace Application;


use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\SessionManager;
use Laminas\Session\Config\SessionConfig;
use Laminas\Session\Container;
use Laminas\Session\Validator;

use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Role\GenericRole as Role;
use Laminas\Permissions\Acl\Resource\GenericResource as Resource;
use Laminas\Authentication\AuthenticationService;
use Laminas\View\Model\ViewModel;
use Application\Service\JWTService;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();

        $em = $application->getEventManager();

        $this->initAcl($e);


        $em->attach('route', [$this, 'checkToken'], -10001);

        $em->attach('route', [$this, 'checkAcl'], -10000);
    }
    public function initAcl(MvcEvent $e)
    {
        $acl = new Acl();

        //RESOURCES
        $acl->addResource(new Resource('album'));
        $acl->addResource(new Resource('blog'));
        $acl->addResource(new Resource('admin'));

        //ROLES
        $acl->addRole(new Role('guest'));
        $acl->deny("guest", "admin");

        $acl->addRole(new Role('staff'), ["guest"]);
        $acl->addRole(new Role('administrator'), ["staff"]);

        // ALLOW
        //$acl->allow("guest", null);

        $acl->deny("guest", "admin");

        $acl->allow('staff', "album");
        $acl->allow('administrator', "admin");

        $e->getViewModel()->acl = $acl;
        // echo $acl->isAllowed('administrator', null, 'update')
        //     ? 'allowed'
        //     : 'denied';

    }

    public function checkAcl(MvcEvent $e)
    {
        $route = $e->getRouteMatch()->getMatchedRouteName();

        // set your role
        $auth = new AuthenticationService();

        $roles = ["someUser", "guest", "administrator"];


        if (!$auth->hasIdentity()) {
            $userRole = 'guest';
        } else {
            $authIdObj = $auth->getIdentity();
            $userRole = $roles[$authIdObj->role_id];

            if (in_array($route, ["admin", "album", "blog"])){
                $this->checkToken($e);
            }
        }

        if (!$e->getViewModel()->acl->hasResource($route)) {
            // NO RESOURCE IN ACL
            $response = $e->getResponse();
            $response->setStatusCode(302);
            $e->getViewModel()->message = 'No Resource - You have to list it in ACL (Err: 200215A)';
        } else {


            if (!$e->getViewModel()->acl->isAllowed($userRole, $route)) {

                $response = $e->getResponse();
                $response->getHeaders()->addHeaderLine('Location', '/error/403');
                $response->setStatusCode(302);
                $response->sendHeaders();
                return $response;

                // NOT ALLOWED
                // if ($auth->hasIdentity()) {
                //     $response = $e->getResponse();
                //     $response->getHeaders()->addHeaderLine('Location', '/login');
                //     $response->setStatusCode(302);
                //     $response->sendHeaders();
                //     return $response;

                // } else {
                //     $response = $e->getResponse();

                //     $response->getHeaders()->addHeaderLine(
                //         'Location',
                //         $e->getRequest()->getBaseUrl() . '/login'
                //     );
                //     $response->setStatusCode(302);
                //     $response->sendHeaders();
                // }



            } else { // IS ALLOWED
                //\Zend\Debug\Debug::dump('IS ALLOWED');  

            }
        }
    }

    public function checkToken(MvcEvent $e)
    {
        $auth = new AuthenticationService();
        $token = $auth->getIdentity()->token;

        if ($token){
            try {
                $decoded = JWTService::checkToken($token);      
               
            } catch (\Exception $ex) {
                
                $response = $e->getResponse();
                $response->getHeaders()->addHeaderLine('Location', '/error/403');
                $response->setStatusCode(403);
                $response->sendHeaders();
                return $response;
            }
        }
    }



    // public function onBootstrap(MvcEvent $e)
    // {
    //     $eventManager        = $e->getApplication()->getEventManager();
    //     $moduleRouteListener = new ModuleRouteListener();
    //     $moduleRouteListener->attach($eventManager);
    //     $this->bootstrapSession($e);
    // }

    // public function bootstrapSession(MvcEvent $e)
    // {
    //     $session = $e->getApplication()
    //         ->getServiceManager()
    //         ->get(SessionManager::class);
    //     $session->start();

    //     $container = new Container('initialized');

    //     if (isset($container->init)) {
    //         return;
    //     }

    //     $serviceManager = $e->getApplication()->getServiceManager();
    //     $request        = $serviceManager->get('Request');

    //     $session->regenerateId(true);
    //     $container->init          = 1;
    //     $container->remoteAddr    = $request->getServer()->get('REMOTE_ADDR');
    //     $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');

    //     $config = $serviceManager->get('Config');
    //     if (! isset($config['session'])) {
    //         return;
    //     }

    //     $sessionConfig = $config['session'];

    //     if (! isset($sessionConfig['validators'])) {
    //         return;
    //     }

    //     $chain   = $session->getValidatorChain();

    //     foreach ($sessionConfig['validators'] as $validator) {
    //         switch ($validator) {
    //             case Validator\HttpUserAgent::class:
    //                 $validator = new $validator($container->httpUserAgent);
    //                 break;
    //             case Validator\RemoteAddr::class:
    //                 $validator  = new $validator($container->remoteAddr);
    //                 break;
    //             default:
    //                 $validator = new $validator();
    //                 break;
    //         }

    //         $chain->attach('session.validate', array($validator, 'isValid'));
    //     }
    // }

    // public function getServiceConfig()
    // {
    //     return [
    //         'factories' => [
    //             SessionManager::class => function ($container) {
    //                 $config = $container->get('config');
    //                 if (! isset($config['session'])) {
    //                     $sessionManager = new SessionManager();
    //                     Container::setDefaultManager($sessionManager);
    //                     return $sessionManager;
    //                 }

    //                 $session = $config['session'];

    //                 $sessionConfig = null;
    //                 if (isset($session['config'])) {
    //                     $class = isset($session['config']['class'])
    //                         ?  $session['config']['class']
    //                         : SessionConfig::class;

    //                     $options = isset($session['config']['options'])
    //                         ?  $session['config']['options']
    //                         : [];

    //                     $sessionConfig = new $class();
    //                     $sessionConfig->setOptions($options);
    //                 }

    //                 $sessionStorage = null;
    //                 if (isset($session['storage'])) {
    //                     $class = $session['storage'];
    //                     $sessionStorage = new $class();
    //                 }

    //                 $sessionSaveHandler = null;
    //                 if (isset($session['save_handler'])) {
    //                     // class should be fetched from service manager
    //                     // since it will require constructor arguments
    //                     $sessionSaveHandler = $container->get($session['save_handler']);
    //                 }

    //                 $sessionManager = new SessionManager(
    //                     $sessionConfig,
    //                     $sessionStorage,
    //                     $sessionSaveHandler
    //                 );

    //                 Container::setDefaultManager($sessionManager);
    //                 return $sessionManager;
    //             },
    //         ],
    //     ];
    // }
}