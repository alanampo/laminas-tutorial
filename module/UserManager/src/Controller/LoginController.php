<?php

declare(strict_types=1);

namespace UserManager\Controller;

//use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;

use Laminas\Authentication\AuthenticationService;


use Laminas\Authentication\Result;

use Laminas\Db\Adapter\Adapter;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\SessionManager;
use Laminas\View\Model\ViewModel;
use UserManager\Form\Auth\LoginForm;
use UserManager\Model\Table\UsersTable;
use Laminas\Crypt\Password\Bcrypt;

use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;

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

			$hash = password_hash($password, PASSWORD_DEFAULT);

			$passwordValidation = function ($hash, $password) {
				return password_verify($password, $hash);
			};

			// Configure the instance with constructor parameters:
			$authAdapter = new AuthAdapter(
				$this->adapter,
				'users',
				'email',
				'password',
				$passwordValidation
			);

			$authAdapter
				->setIdentity($email)
				->setCredential($password);

			// Perform the authentication query, saving the result
			$result = $authAdapter->authenticate();

			switch ($result->getCode()) {

				case Result::FAILURE_IDENTITY_NOT_FOUND:
					$this->flashMessenger()->addErrorMessage('Not found');
					return $this->redirect()->toRoute('login');
					break;
			
				case Result::FAILURE_CREDENTIAL_INVALID:
					/** do stuff for invalid credential **/
					$this->flashMessenger()->addErrorMessage('Invalid credentials!');
					return $this->redirect()->toRoute('login');
					break;
			
				case Result::SUCCESS:
					$this->flashMessenger()->addSuccessMessage('You have logged in successfully');
					return $this->redirect()->toRoute('admin');
					break;
			
				default:
					/** do stuff for other failure **/
					break;
			}

			//verifico login e password

			//se ok redirect to home page 

			//se ko ritorna alla login

		} else {
			return new ViewModel();
		}



		die("index post");
		return new ViewModel();
	}
	// public function index2Action()
	// {
	// 	$auth = new AuthenticationService();
	// 	if ($auth->hasIdentity()) {
	// 		return $this->redirect()->toRoute('home');
	// 	}

	// 	$loginForm = new LoginForm();
	// 	$loginForm->get('returnUrl')->setValue(
	// 		$this->getEvent()->getRouteMatch()->getParam('returnUrl')
	// 	);

	// 	$request = $this->getRequest();

	// 	if ($request->isPost()) {
	// 		$formData = $request->getPost()->toArray();
	// 		$loginForm->setInputFilter($this->usersTable->getLoginFormFilter());
	// 		$loginForm->setData($formData);

	// 		if ($loginForm->isValid()) {
	// 			$authAdapter = new CredentialTreatmentAdapter($this->adapter);
	// 			$authAdapter->setTableName($this->usersTable->getTable())
	// 				->setIdentityColumn('email')
	// 				->setCredentialColumn('password')
	// 				->getDbSelect()->where(['active' => 1]);

	// 			# data from loginForm
	// 			$data = $loginForm->getData();
	// 			$returnUrl = $this->params()->fromPost('returnUrl');
	// 			$authAdapter->setIdentity($data['email']);

	// 			$hash = new Bcrypt();

	// 			# well let us use the email address from the form to retrieve data for this user
	// 			$info = $this->usersTable->fetchAccountByEmail($data['email']);

	// 			# now compare password from form input with that already in the table
	// 			if ($hash->verify($data['password'], $info->getPassword())) {
	// 				$authAdapter->setCredential($info->getPassword());
	// 			} else {
	// 				$authAdapter->setCredential(''); # why? to gracefully handle errors
	// 			}

	// 			$authResult = $auth->authenticate($authAdapter);

	// 			switch ($authResult->getCode()) {
	// 				case Result::FAILURE_IDENTITY_NOT_FOUND:
	// 					$this->flashMessenger()->addErrorMessage('Unknow email address!');
	// 					return $this->redirect()->refresh();
	// 					//return $this->redirect()->refresh(); # refresh the page to show error
	// 					break;

	// 				case Result::FAILURE_CREDENTIAL_INVALID:
	// 					$this->flashMessenger()->addErrorMessage('Incorrect Password!');
	// 					return $this->redirect()->refresh(); # refresh the page to show error
	// 					break;

	// 				case Result::SUCCESS:
	// 					if ($data['recall'] == 1) {
	// 						$ssm = new SessionManager();
	// 						$ttl = 3600; # time for session to live
	// 						$ssm->rememberMe($ttl);
	// 					}

	// 					$storage = $auth->getStorage();
	// 					$storage->write($authAdapter->getResultRowObject(null, ['created', 'modified']));

	// 					if (!empty($returnUrl)) {
	// 						return $this->redirect()->refresh();
	// 					}
	// 					$this->flashMessenger()->addSuccessMessage("You have logged in successfully");
	// 					# let us now create the profile route and we will be done
	// 					return $this->redirect()->toRoute(
	// 						'home',
	// 						[
	// 							'id' => $info->getUserId(),
	// 							'username' => mb_strtolower($info->getUsername()),
	// 							'displayname' => $info->getDisplayName()
	// 						]
	// 					);

	// 					break;

	// 				default:
	// 					$this->flashMessenger()->addErrorMessage('Authentication failed. Try again');
	// 					return $this->redirect()->refresh();
	// 					break;
	// 			}
	// 		}
	// 	}

	// 	return (new ViewModel(['form' => $loginForm]))->setTemplate('user-manager/auth/login');
	// }

}