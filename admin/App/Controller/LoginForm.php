<?php

	use Livro\Controller\Controller;
	use Livro\Controller\Action;
	use Livro\Widgets\Form\Form;
	use Livro\Widgets\Form\Entry;
	use Livro\Widgets\Form\Password;
	use Livro\Widgets\Wrapper\FormWrapper;
	use Livro\Widgets\Container\Panel;
	use Livro\Database\Transaction;
	use Livro\Database\Repository;
	use Livro\Database\Criteria;

	use Livro\Session\Session;

	class LoginForm extends Controller
	{

		private $form; // formulário

		// Contrutor da página
		public function __construct()
		{
			parent::__construct();

			// Formulário
			$this->form = new FormWrapper( new Form('form_login') );
			$this->form->setTitle('Login');

			$login 		= new Entry('login');
			$password	= new Password('password');

			$login->placeholder 		= 'admin';
			$password->placeholder 	= 'admin';

			$this->form->addField('Login', $login, 200);
			$this->form->addField('Senha', $password, 200);
			$this->form->addAction('Login', new Action(array($this, 'onLogin')) );

			// Adiciona o formulário na página
			parent::add($this->form);

		}

		// Login
		public function onLogin($param)
		{
			$data = $this->form->getData();

			Transaction::open('cms');

			$user = User::findByLogin($data->login);

			if( $user instanceof User)
			{
				
				if( $user->validatePassword($data->password) )
				{
					Session::setValue('logged', TRUE);
          echo "<script language='JavaScript'> window.location = 'index.php'; </script>";
				}
				
			}

		}

		// Logout
		public function onLogout($param)
		{
			Session::setValue('logged', FALSE);
      echo "<script language='JavaScript'> window.location = 'index.php'; </script>";
		}

	}	