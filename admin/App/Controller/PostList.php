<?php

	use Livro\Controller\Controller;
	use Livro\Controller\Action;
	use Livro\Widgets\Form\Form;
	use Livro\Widgets\Form\Entry;
	use Livro\Widgets\Form\Combo;
	use Livro\Widgets\Container\Panel;
	use Livro\Widgets\Container\VBox;
	use Livro\Widgets\Datagrid\Datagrid;
	use Livro\Widgets\Datagrid\DatagridColumn;
	use Livro\Widgets\Datagrid\PageNavigation;
	use Livro\Widgets\Dialog\Message;
	use Livro\Widgets\Dialog\Question;
	use Livro\Widgets\Wrapper\FormWrapper;
	use Livro\Widgets\Wrapper\DatagridWrapper;
	use Livro\Database\Transaction;
	use Livro\Database\Repository;
	use Livro\Database\Criteria;

	class PostList extends Controller
	{
		private $form;     // formulário de buscas
    private $datagrid; // listagem
    private $loaded;
	
    // Construtor de página
    public function __construct()
    {
  	  parent::__construct();

      // instancia um formulário
      $this->form = new FormWrapper(new Form('form_busca_pessoas'));
      $this->form->setTitle('Posts');
      
      // cria os campos do formulário
      $title = new Entry('title');
      
      $this->form->addField('Título', $title, 300);
      $this->form->addAction('Buscar', new Action(array($this, 'onReload')));
      $this->form->addAction('Novo', new Action(array(new PostForm, 'onEdit')));
      
      // instancia objeto Datagrid
      $this->datagrid = new DatagridWrapper(new Datagrid);

      // instancia as colunas da Datagrid
      $codigo   = new DatagridColumn('id',         'Código', 'right', 50);
      $title    = new DatagridColumn('title',      'Título', 'left', null);
      $subtitle = new DatagridColumn('subtitle',   'Subtítulo', 'left', null);
      $tag      = new DatagridColumn('tag',        'Tag', 'left', null);

      // adiciona as colunas à Datagrid
      $this->datagrid->addColumn($codigo);
      $this->datagrid->addColumn($title);
      $this->datagrid->addColumn($subtitle);
      $this->datagrid->addColumn($tag);

      $this->datagrid->addAction( 'Editar',  new Action([new PostForm, 'onEdit']), 'id', 'fa fa-edit fa-lg blue');
      $this->datagrid->addAction( 'Excluir', new Action([$this, 'onDelete']),          'id', 'fa fa-trash fa-lg red');

      $this->pagenav = new PageNavigation;
      $this->pagenav->setAction( new Action(array($this, 'onReload')));

       // monta a página através de uma caixa
      $box = new VBox;
      $box->style = 'display:block';
      $box->add($this->form);
      $box->add($this->datagrid);
      $box->add($this->pagenav);
      
      parent::add($box);

    }

    // Carrega a Datagrid com os objetos do banco de dados
    public function onReload($param)
    {
    	Transaction::open('cms'); // inicia transação com o BD

      $repository = new Repository('Post');
        
      // obtém os dados do formulário de buscas
      $dados = $this->form->getData();
        
      // cria um critério de seleção de dados
      $criteria = new Criteria;

      // verifica se o usuário preencheu o formulário
      if ($dados->title)
      {
        // filtra pelo title
        $criteria->add('title', 'like', "%{$dados->title}%");
      }

      // carrega os produtos que satisfazem o critério
      $count   = $repository->count($criteria);
        
      $criteria->setProperty('order', 'id');
      $criteria->setProperty('limit', 10);
      $criteria->setProperty('offset', isset($param['offset']) ? (int) $param['offset'] : 0);
      $pessoas = $repository->load($criteria);

      $this->datagrid->clear();
      if ($pessoas)
      {
        foreach ($pessoas as $pessoa)
        {
          // adiciona o objeto na Datagrid
          $this->datagrid->addItem($pessoa);
        }
      }

      $this->pagenav->setTotalRecords( $count );
      $this->pagenav->setCurrentPage( isset($param['page']) ? (int) $param['page'] : 1 );

      // finaliza a transação
      Transaction::close();
      
      $this->loaded = true;
      
    }

    // Pergunta sobre a exclusão de registro
    public function onDelete($param)
    {
    	$key = $param['key']; // obtém o parâmetro $key
      $action1 = new Action(array($this, 'Delete'));
      $action1->setParameter('key', $key);
        
      new Question('Deseja realmente excluir o registro?', $action1);
    }

    // Exclui um registro
    public function Delete($param)
    {
    	try
      {
        $key = $param['key']; // obtém a chave

        Transaction::open('cms'); // inicia transação com o banco 'cms'

        $cidade = new Post($key); // instancia objeto Cidade
        $cidade->delete(); // deleta objeto do banco de dados

        Transaction::close(); // finaliza a transação

        $this->onReload(); // recarrega a datagrid
        new Message('info', "Registro excluído com sucesso");
      }
      catch (Exception $e)
      {
      	new Message('error', $e->getMessage());
      }
    }

    // Exibe a página
    public function show()
    {
    	// se a listagem ainda não foi carregada
    	if( !$this->loaded )
    	{
    		$this->onReload( $_GET );
    	}

    	parent::show();

    }

	}