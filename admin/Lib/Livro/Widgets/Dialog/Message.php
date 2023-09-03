<?php

  namespace Livro\Widgets\Dialog;

  use Livro\Widgets\Base\Elements;

  class Message{

        /**
     * Instancia a mensagem
     * @param $type      = tipo de mensagem (info, error)
     * @param $message = mensagem ao usuário
     */
    public function __construct($type, $message){
        $div = new Elements('div');
        $div->style = 'margin:20px;';
        if ($type == 'info'){
            $div->class = 'alert alert-info';
        }
        else if ($type == 'error'){
            $div->class = 'alert alert-danger';
        }
        $div->add($message);
        $div->show();
    }

  }