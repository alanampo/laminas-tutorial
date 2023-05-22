<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class RequestController extends AbstractActionController
{
    public function indexAction()
    {
        /* $this->flashMessenger()->addSuccessMessage("Alan Ampo"); */
        $v = new ViewModel();
        //$v->setTerminal(true);
        return $v;
    }
}
