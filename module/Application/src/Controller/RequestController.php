<?php

declare(strict_types=1);

namespace Application\Controller;

use Exception;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Log\Logger;

class RequestController extends AbstractActionController
{
    
    private $logger;
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function indexAction()
    {
        //1 / 0;
        
       
        // try {
        //     throw new Exception("Prova 1");
            
        // } catch (\Throwable $e) {
        //     $this->logger->log(Logger::INFO, $e->getMessage());
        //     throw new Exception("ERROR: ".$e->getMessage());
        // }

        $response = $this->getResponse();
        $response->setStatusCode(203);
        
        
        
        $v = new ViewModel();
        
        return $v;
    }
}
