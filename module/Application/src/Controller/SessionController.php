<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\AuthenticationService;

class SessionController extends AbstractActionController
{
    public function indexAction()
    {
        if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > 1000)) {

            //Unset the session variables

            session_unset();

            //Destroy the session

            session_destroy();

            $auth = new AuthenticationService();
            if ($auth->hasIdentity()) {
                $auth->clearIdentity();
            }


            $this->flashMessenger()->addErrorMessage('Session expired after 5 seconds');

        }

        return new ViewModel();
    }


}