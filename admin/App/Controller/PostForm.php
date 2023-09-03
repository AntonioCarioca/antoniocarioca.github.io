<?php

	use Livro\Controller\Controller;
	use Livro\Controller\Action;
	use Livro\Widgets\Form\Form;
	use Livro\Widgets\Dialog\Message;
	use Livro\Widgets\Form\Entry;
	use Livro\Widgets\Form\Text;
	use Livro\Widgets\Form\Date;
	use Livro\Database\Transaction;
	use Livro\Widgets\Container\Panel;
	use Livro\Widgets\Wrapper\FormWrapper;

	class PostForm extends COntroller
	{

		private $form;

		// Construtor da página
		public function __construct()
		{
			parent::__construct();
        
      // instancia um formulário
      $this->form = new FormWrapper(new Form('form_posts'));
      $this->form->setTitle('Post');
      
      // cria os campos do formulário
      $codigo    = new Entry('id');
      $title     = new Entry('title');
      $subtitle  = new Entry('subtitle');
      $content   = new Text('content');
      $post_date = new Date('post_date');
      $tag       = new Entry('tag');
      
      $content->setSize('100%', '200px');

      $this->form->addField('Código',    $codigo,    '30%');
      $this->form->addField('Título',    $title,     '70%');
      $this->form->addField('Subtítulo', $subtitle,  '70%');
      $this->form->addField('Conteúdo',  $content,   '70%');
      $this->form->addField('Data',      $post_date, '70%');
      $this->form->addField('Tag',       $tag,       '70%');
      
      // define alguns atributos para os campos do formulário
      $codigo->setEditable(FALSE);
      
      $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
      
      // adiciona o formulário na página
      parent::add($this->form);
		}

		// Salva os dados do formulário
		public function onSave()
		{
			try
			{
				// inicia transação com o BD
        Transaction::open('cms');

        $dados = $this->form->getData();
        $this->form->setData($dados);
        $post = new Post; // instancia objeto
        $post->fromArray( (array) $dados); // carrega os dados
        $post->store(); // armazena o objeto no banco de dados
        
        Transaction::close(); // finaliza a transação
        new Message('info', 'Dados armazenados com sucesso');
			}
			catch(Exception $e)
			{
				// exibe a mensagem gerada pela exceção
        new Message('error', $e->getMessage());

        // desfaz todas alterações no banco de dados
        Transaction::rollback();
			}
		}

		// Carrega registro para edição
		public function onEdit()
		{
			try
			{

				if( isset($param['id']) )
				{

					$id = $param['id']; // obtém a chave
          Transaction::open('cms'); // inicia transação com o BD

          $post = Post::find($id);
          if( $post )
          {
          	$this->form->setData($post); // lança os dados da post no formulário
          }

          Transaction::close(); // finaliza a transação
          
				}

			}
			catch(Exception $e) // em caso de exceção
			{
				// exibe a mensagem gerada pela exceção
        new Message('error', $e->getMessage());
        // desfaz todas alterações no banco de dados
        Transaction::rollback();
			}

		}

	}