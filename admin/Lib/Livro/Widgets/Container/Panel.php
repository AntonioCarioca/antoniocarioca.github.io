<?php

  namespace Livro\Widgets\Container;

  use Livro\Widgets\Base\Elements;

  class Panel extends Elements{

    private $body;
    private $footer;
    
    /**
     * Constrói o painel
     */
    public function __construct($panel_title = NULL){
        parent::__construct('div');
        $this->class = 'panel panel-default';
        
        if ($panel_title){
            $head = new Elements('div');
            $head->class = 'panel-heading';
        
            $label = new Elements('h4');
            $label->add($panel_title);
            
            $title = new Elements('div');
            $title->class = 'panel-title';
            $title->add( $label );
            $head->add($title);
            parent::add($head);
        }
        
        $this->body = new Elements('div');
        $this->body->class = 'panel-body';
        parent::add($this->body);
        
        $this->footer = new Elements('div');
        $this->footer->{'class'} = 'panel-footer';
        
    }
    
    /**
     * Adiciona conteúdo
     */
    public function add($content){
        $this->body->add($content);
    }
    
    /**
     * Adiciona rodapé
     */
    public function addFooter($footer){
        $this->footer->add( $footer );
        parent::add($this->footer);
    }

} 