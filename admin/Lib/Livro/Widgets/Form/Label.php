<?php

  namespace Livro\Widgets\Form;

  use Livro\Widgets\Base\Elements;

/**
 * Representa um rótulo de texto
 * @author Pablo Dall'Oglio
 */
class Label extends Field implements FormElementsInterface
{
    private $tag;
    
    /**
     * Construtor
     * @param $value text label
     */
    public function __construct($value)
    {
        // set the label's content
        $this->setValue($value);
        
        // create a new element
        $this->tag = new Elements('label');
    }
    
    /**
     * Adiciona conteúdo no label
     */
    public function add($child)
    {
        $this->tag->add($child);
    }
    
    /**
     * Exibe o widget
     */
    public function show()
    {
        $this->tag->add($this->value);
        $this->tag->show();
    }
}
