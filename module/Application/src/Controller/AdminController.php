<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Log\Logger;
class AdminController extends AbstractActionController
{

    private $logger;
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function indexAction()
    {
                
        // $this->logger->log(Logger::INFO, 'Informational message');
        // $this->logger->info('Informational message');

        return new ViewModel();
    }

}
