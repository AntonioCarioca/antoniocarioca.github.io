<?php

	namespace Livro\Traits;

	use Livro\Database\Transaction;
	use Livro\Widgets\Dialog\Message;
	use Exception;

	trait SaveTrait
	{
		 /**
     * Salva os dados do formulário
     */

     function onSave()
     {

     	try
     	{
     		Transaction::open($this->connection);

     		$class = $this->activeRecord;
     		$dados = $this->form->getData();

     		$object = new $class;
     		$object->fromArray( (array) $dados );
     		$object->store();

     		$dados->id = $object->id;
     		$this->form->setData($dados);

     		Transaction::close();
     		new Message('info', 'Dados armazenados com sucesso');
     	}
     	catch(Exception $e)
     	{
     		new Message('error', $e->getMessage());
     	}

     }
	}