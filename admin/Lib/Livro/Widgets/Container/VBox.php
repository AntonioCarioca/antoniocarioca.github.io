<?php

  namespace Livro\Widgets\Container;

  use Livro\Widgets\Base\Elements;

/**
 * Caixa vertical
 * @author Pablo Dall'Oglio
 */
class VBox extends Elements{
    /**
     * Método construtor
     */
    public function __construct(){
        parent::__construct('div');
        $this->{'style'} = 'display: inline-block';
    }
    
    /**
     * Adiciona um elemento filho
     * @param $child Objeto filho
     */
    public function add($child){
        $wrapper = new Elements('div');
        $wrapper->{'style'} = 'clear:both';
        $wrapper->add($child);
        parent::add($wrapper);
        return $wrapper;
    }

  }