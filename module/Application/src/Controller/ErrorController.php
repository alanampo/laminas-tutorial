<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\AuthenticationService;

class ErrorController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function i403Action()
    {
        return new ViewModel();
    }


}