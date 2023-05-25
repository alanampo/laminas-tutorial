<?php

declare(strict_types=1);

namespace Application;

use Album\Controller\AlbumController;
use Application\Controller\AdminController;
use Application\Controller\ApiController;
use Application\Controller\IndexController;
use Laminas\Mvc\MvcEvent;

use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Role\GenericRole as Role;
use Laminas\Permissions\Acl\Resource\GenericResource as Resource;
use Laminas\Authentication\AuthenticationService;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Application\Service\JWTService;
use Blog\Controller\ListController;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream as LogWriterStream;
use UserManager\Controller\AuthController;
use UserManager\Controller\LoginController;
use UserManager\Controller\LogoutController;

use Laminas\Http\Request as HttpRequest;

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

        // Service Manager
        $sm  = $application->getServiceManager();
        
        // GETTING CONFIG 
        $config     = $sm->get('config');
        
        $aclrules   = $config['acl']['aclrules'];
        $aclroles   = $config['acl']['aclroles'];

        $this->initAcl($e, $aclrules, $aclroles);

        $em->attach('route', [$this, 'checkToken'], -10001);

        $em->attach('route', [$this, 'checkAcl'], -10000);

        $em->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError'], -1000);
        
        $em->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatchError'], -1000);

        $em->attach(MvcEvent::EVENT_ROUTE, [$this, 'onDispatchError'], -1000);

        $em->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], -1000);
        
        $em->attach(MvcEvent::EVENT_RENDER_ERROR, [$this,'onDispatchError'], - 100);        
    }

    function onDispatchError(MvcEvent $event) {
        $viewModel = $event->getViewModel();

        $response = $event->getResponse();
        $route = $event->getRouteMatch()->getMatchedRouteName();
        
        if($response->getStatusCode() == 401){  
            if ($route == "api") {
                $event->setViewModel(new JsonModel([    
                    "message" => "401 - Not Authorized"
                ]));
            }           
            else{
                $childModel = new ViewModel();
                $childModel->setTemplate('error/401');
                $viewModel->addChild($childModel,'content');   
            }
        }
        elseif($response->getStatusCode() == 403){  
            if ($route == "api") {
                $event->setViewModel(new JsonModel([    
                    "message" => "403 - Forbidden"
                ]));
            }           
            else{
                $childModel = new ViewModel();
                $childModel->setTemplate('error/403');
                $viewModel->addChild($childModel,'content');
            }
        }
        else if($response->getStatusCode() == 500){  
            $childModel = new ViewModel();
            $childModel->setTemplate('error/index');
            $viewModel->addChild($childModel,'content');   
        }

    }

    public function initAcl(MvcEvent $e, $aclrules, $aclroles)
    {
        $acl = new Acl();
        $allResources = [];

        foreach ($aclrules as $role => $resources) {
            $parents =$aclroles[$role]['parent'];
            
            $role = new Role($role);
            
            $acl->addRole($role,$parents);
            $allResources = array_merge($resources["routes"], $allResources);
            
            //adding resources
            foreach ($resources as $resourceType) {
                foreach ($resourceType as $resource) {
                    try{
                        if(!$acl->hasResource($resource)){
                            $acl->addResource(new Resource($resource));
                            $acl -> allow($role, $resource);
                        }
                    } catch (\Throwable $e) {
                        die("Resource $resource already exists");
                    }
                }
            }
        }
        $e->getViewModel()->acl = $acl;
    }

    public function checkAcl(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        
        // GETTING CONFIG 
        $config     = $sm->get('config');
        $aclroles   = $config['acl']['aclroles'];

        $response = $e->getResponse();
        
        $route = $e->getRouteMatch()->getMatchedRouteName();
        
        // set your role
        $auth = new AuthenticationService();

        if (!$auth->hasIdentity() ) {
            $userRole = 'guest';                        
        } else {
            $authIdObj = $auth->getIdentity();
            $userRole = $authIdObj->roleName;

            $roleFound = false;
            foreach ($aclroles as $key => $value) {
                if ($key == $userRole){
                    $roleFound = true;
                    break;
                }
            }

            if (!$roleFound){
                die("The user's role stored on DB has not found in the ACL config");
            }
            
            $this->checkToken($e);
            
        }
        
        if (!$e->getViewModel()->acl->hasResource($route)) {
            // NO RESOURCE IN ACL
            $e->getViewModel()->message = 'No Resource';
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine(
                'Location',
                $e->getRequest()->getBaseUrl() . '/'
            ); 
            $response->setStatusCode(302);
            return $response;
        } else {
            if (!$e->getViewModel()->acl->isAllowed($userRole, $route)) {// NOT ALLOWED
                //$response->getHeaders()->addHeaderLine('Location', '/error/403'); -- PARA REDIRECCIONAR
                //$response->sendHeaders();
                if ($auth->hasIdentity()) {
                    $response = $e->getResponse();
                    $response->setStatusCode(403);
                } else {
                    $response = $e->getResponse();
                   /*  $response->getHeaders()->addHeaderLine(
                        'Location',
                        $e->getRequest()->getBaseUrl() . '/login'
                    ); */
                    $response->setStatusCode(401);
                    //$response->sendHeaders();
                }
            } else { // IS ALLOWED
                
            }
        }
    }

    //FARE CONTROLLO DELL'AUTORIZZAZIONE SUI CONTROLLER
    function onDispatch(MvcEvent $event) {
        $match      = $event->getRouteMatch();
        
        $request    = $event->getRequest();
        //$method     = $request instanceof HttpRequest ? strtolower($request->getMethod()) : null;
        
        $controllerRequest = $match->getParam('controller'); 
        $controller = explode("\\", $controllerRequest);
        
        $controller = end($controller);
        //$action = $match->getParam('action'); 
        // set your role
        $auth = new AuthenticationService();

        if (!$auth->hasIdentity()) {
            $userRole = 'guest';                        
        } 
        // else {
        //     $authIdObj = $auth->getIdentity();
        //     $userRole = $roles[$authIdObj->role_id];

        //     if (in_array($route, ["admin", "album", "blog", "api"])){
        //         $this->checkToken($e);
        //     }
        // }
       
        $response = $event->getResponse();
        //SE IL CONTROLLER NON É IN acl.inc
        if (!$event->getViewModel()->acl->hasResource($controller)) {
            // NO RESOURCE IN ACL
            // $event->getViewModel()->message = 'No Resource';            
            // $response->getHeaders()->addHeaderLine(
            //     'Location',
            //     $event->getRequest()->getBaseUrl() . '/'
            // ); 
            // $response->setStatusCode(302);
            // return $response;
        } else {
            if (!$event->getViewModel()->acl->isAllowed($userRole, $controller)) {// NOT ALLOWED
                // if ($auth->hasIdentity()) {
                //     $response->setStatusCode(403);
                // } else {
                //     $response->setStatusCode(401);
                // }
            } else { // IS ALLOWED
                
            }
        }

        //print_r($this->getResourceName($controller, $action));
        //die();
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
                $response->setStatusCode(403);
            }
        }
    }

    public function getServiceConfig()
    {
        return [       
            'factories' => [
                'LaminasLog' => function ($sm) {
                    $filename = 'log_' . date('ymd') . '.log';
                    $log = new Logger();
                    $writer = new LogWriterStream('./data/logs/' . $filename);
                    $log->addWriter($writer);
                    return $log;
                },
            ]
        ];
    }
}