<?php

  namespace Livro\Widgets\Dialog;

  use Livro\Controller\Action;
  use Livro\Widgets\Base\Elements;

/**
 * Exibe perguntas ao usuário
 * @author Pablo Dall'Oglio
 */
  class Question{
    /**
     * Instancia o questionamento
     * @param $message = pergunta ao usuário
     * @param $action_yes = ação para resposta positiva
     * @param $action_no = ação para resposta negativa
     */
    function __construct($message, Action $action_yes, Action $action_no = NULL){
        $div = new Elements('div');
        $div->class = 'alert alert-warning question';
        
        // converte os nomes de métodos em URL's
        $url_yes = $action_yes->serialize();
        
        $link_yes = new Elements('a');
        $link_yes->href = $url_yes;
        $link_yes->class = 'btn btn-default';
        $link_yes->style = 'float:right';
        $link_yes->add('Sim');
        
        $message .= '&nbsp;' . $link_yes;
        if ($action_no){
            $url_no = $action_no->serialize();
            
            $link_no = new Elements('a');
            $link_no->href = $url_no;
            $link_no->class = 'btn btn-default';
            $link_no->style = 'float:right';
            $link_no->add('Não');
            
            $message .= $link_no;
        }
        
        $div->add($message);
        $div->show();
    }
  }
