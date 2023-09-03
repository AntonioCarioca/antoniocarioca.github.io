<?php

	use Livro\Controller\Controller;
	use Livro\Controller\Action;
	use Livro\Widgets\Form\Form;
	use Livro\Widgets\Dialog\Message;
	use Livro\Widgets\Form\Entry;
	use Livro\Widgets\Form\Password;
	use Livro\Database\Transaction;
	use Livro\Widgets\Container\Panel;
	use Livro\Widgets\Wrapper\FormWrapper;

	// Formulário de users
	class UserForm extends Controller
	{

		private $form;

		/**
    * Construtor da página
    */
    public function __construct()
    {
      parent::__construct();
        
      // instancia um formulário
      $this->form = new FormWrapper(new Form('form_users'));
      $this->form->setTitle('User');
        
      // cria os campos do formulário
      $codigo    = new Entry('id');
      $login     = new Entry('login');
      $name      = new Entry('name');
      $password  = new Password('password');
      
      $this->form->addField('Código',    $codigo,    '30%');
      $this->form->addField('Login',     $login,     '70%');
      $this->form->addField('Name',      $name,      '70%');
      $this->form->addField('Password',  $password,  '70%');
      
      // define alguns atributos para os campos do formulário
      $codigo->setEditable(FALSE);
      
      $this->form->addAction('Salvar', new Action(array($this, 'onSave')) );
      
      // adiciona o formulário na página
      parent::add($this->form);
    }

    /**
    * Salva os dados do formulário
    */
    public function onSave()
    {
      try
      {
        // inicia transação com o BD
        Transaction::open('cms');

        $dados = $this->form->getData();
        $this->form->setData($dados);
        $user = new User; // instancia objeto
        
        $user->fromArray( (array) $dados); // carrega os dados

        if ( !empty($user->password) )
        {
          $user->password = hash('sha256', $user->password);
        }
        else
        {
          unset($user->password);
        }
        
        $user->store(); // armazena o objeto no banco de dados
        
        Transaction::close(); // finaliza a transação

        new Message('info', 'Dados armazenados com sucesso');
      }
      catch (Exception $e)
      {
        // exibe a mensagem gerada pela exceção
        new Message('error', $e->getMessage());

        // desfaz todas alterações no banco de dados
        Transaction::rollback();
      }

    }

    /**
    * Carrega registro para edição
    */
    public function onEdit($param)
    {
      try
      {
        if ( isset($param['id']) )
        {
          $id = $param['id']; // obtém a chave

          Transaction::open('cms'); // inicia transação com o BD

          $user = User::find($id);
          unset($user->password);

          if ( $user )
          {
          	$this->form->setData($user); // lança os dados da user no formulário
          }

          Transaction::close(); // finaliza a transação

        }
      }
      catch (Exception $e)// em caso de exceção
      {
        // exibe a mensagem gerada pela exceção
        new Message('error', $e->getMessage());
        // desfaz todas alterações no banco de dados
        Transaction::rollback();
		  }

    }

	}