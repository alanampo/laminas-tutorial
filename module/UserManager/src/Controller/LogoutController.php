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

		session_destroy();
		
		setcookie("alantest-email", "", time() - 3600, "/");

		return $this->redirect()->toRoute('login');
	}
}
