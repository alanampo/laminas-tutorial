<?php

declare(strict_types=1);

namespace UserManager\Controller;

use Laminas\Authentication\AuthenticationService;


use Laminas\Authentication\Result;
use Laminas\Db\Adapter\Adapter;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container as SessionContainer; 


use Laminas\Session\Storage\SessionStorage;
use Laminas\View\Model\ViewModel;
use UserManager\Model\Table\UsersTable;
//use Laminas\Crypt\Password\Bcrypt;

use Application\Service\JWTService;

use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Laminas\Session\Storage\ArrayStorage;

class LoginController extends AbstractActionController
{
	private $adapter; # database adapter
	private $usersTable; # table we store data in

	public function __construct(Adapter $adapter, UsersTable $usersTable)
	{
		$this->adapter = $adapter;
		$this->usersTable = $usersTable;

	}

	public function indexAction()
	{
		$request = $this->getRequest();

		if ($request->isPost()) {
			$data = $request->getPost();

			$auth = new AuthenticationService();

			$email = $data->email;
			$password = $data->password;

			//verifico login e password
			$hash = password_hash($password, PASSWORD_DEFAULT);

			$passwordValidation = function ($hash, $password) {
				return password_verify($password, $hash);
			};

			// Configure the instance with constructor parameters:
			$authAdapter = new AuthAdapter(
				$this->adapter,
				'users', //TABLE NAME
				'email', //UNIQUE KEY
				'password',
				$passwordValidation
			);

			$authAdapter
				->setIdentity($email)
				->setCredential($password)
				;

			// Perform the authentication query, saving the result
			$result = $authAdapter->authenticate();

			switch ($result->getCode()) {

				case Result::FAILURE_IDENTITY_NOT_FOUND:
					//se ko ritorna alla login
					$this->flashMessenger()->addErrorMessage('Not found');
					return $this->redirect()->toRoute('login');


				case Result::FAILURE_CREDENTIAL_INVALID:
					//se ko ritorna alla login
					$this->flashMessenger()->addErrorMessage('Invalid credentials!');
					return $this->redirect()->toRoute('login');

				case Result::SUCCESS:
					//se ok redirect to home page 
					$container = new SessionContainer('adminsession');	 
					$container->email = $email;

					$storage = $auth->getStorage();
					
					// print_r($authAdapter->getResultRowObject(null, ['created', 'modified']));
					// die();
					
					
					
					$obj = $authAdapter->getResultRowObject(null, ['created', 'modified']);
					$obj->token = JWTService::createToken((array)$identity);
					
					$roleName = $this->usersTable->getRoleName($obj->role_id);

					$obj->roleName = $roleName;
					
					$storage->write($obj);


					
					//print_r($auth->getIdentity());
					//die();
					
					
					
					
					
					
					

					
					
					
					


					// print_r($auth->getIdentity());
					// die();

					// echo "<pre>";
					// print_r($auth->getIdentity());
					// echo "</pre>";
					// die();
					

					$this->flashMessenger()->addSuccessMessage('You have logged in successfully');
					return $this->redirect()->toRoute('home');

				default:
					/** do stuff for other failure **/
					break;
			}
		}
		return new ViewModel();
	}
}