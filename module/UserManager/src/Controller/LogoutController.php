<?php

declare(strict_types=1);

namespace UserManager\Controller;

use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;

class LogoutController extends AbstractActionController
{
	public function indexAction()
	{
		$auth = new AuthenticationService();
		if($auth->hasIdentity()) {
			$auth->clearIdentity();
		}

		return $this->redirect()->toRoute('login');
	}
}
