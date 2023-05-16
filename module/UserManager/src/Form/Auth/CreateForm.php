<?php

declare(strict_types=1);

namespace UserManager\Form\Auth;

use Laminas\Form\Form;
use Laminas\Form\Element;

class CreateForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('new_account');
		$this->setAttribute('method', 'post');

		# username input field
		$this->add([
			'type' => Element\Text::class,
			'name' => 'username',
			'options' => [
				'label' => 'Username'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'pattern' => '^[a-zA-Z0-9]+$',  # enforcing what type of data we accept
				'data-toggle' => 'tooltip',
				'class' => 'form-control input-username',   # styling the text field
				'title' => 'Username must consist of alphanumeric characters only',
				'placeholder' => 'Enter Your Username'
			]
		]);

		# displayname input field
		$this->add([
			'type' => Element\Text::class,
			'name' => 'displayname',
			'options' => [
				'label' => 'Full Name'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 40,
				'pattern' => "^[a-zA-Z '.-]*$",  # enforcing what type of data we accept
				'data-toggle' => 'tooltip',
				'class' => 'form-control input-displayname',   # styling the text field
				'title' => 'Full Name must consist of letters only',
				'placeholder' => 'Enter Your Full Name'
			]
		]);

	
		# email address input field
		$this->add([
			'type' => Element\Email::class,
			'name' => 'email',
			'options' => [
				'label' => 'Email Address'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control input-email',
				'title' => 'Provide a valid and working email address',
				'placeholder' => 'Enter Your Email Address'
			]
		]);

		# confirm email address
		$this->add([
			'type' => Element\Email::class,
			'name' => 'confirm_email',
			'options' => [
				'label' => 'Verify Email Address'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control input-email',
				'title' => 'Email address must match that provided above',
				'placeholder' => 'Enter Your Email Address Again'
			]
		]);

		# password input field
		$this->add([
			'type' => Element\Password::class,
			'name' => 'password',
			'options' => [
				'label' => 'Password'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',   # styling
				'title' => 'Password must have between 8 and 25 characters',
				'placeholder' => 'Enter Your Password'
			]
		]);

		# confirm password input field
		$this->add([
			'type' => Element\Password::class,
			'name' => 'confirm_password',
			'options' => [
				'label' => 'Verify Password'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',   # styling
				'title' => 'Password must match that provided above',
				'placeholder' => 'Enter Your Password Again'
			]
		]);

		# cross-site-request forgery (csrf) field
		$this->add([
			'type' => Element\Csrf::class,
			'name' => 'csrf',
			'options' => [
				'csrf_options' => [
					'timeout' => 600,  # 5 minutes
				]
			]
		]);

		# submit button
		$this->add([
			'type' => Element\Submit::class,
			'name' => 'create_account',
			'attributes' => [
				'value' => 'Create Account',
				'class' => 'btn btn-primary'
			]
		]);
	}
}

