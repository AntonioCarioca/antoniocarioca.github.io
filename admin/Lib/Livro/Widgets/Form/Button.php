<?php

  namespace Livro\Widgets\Form;

  use Livro\Controller\Action;
  use Livro\Controller\ActionInterface;
  use Livro\Widgets\Base\Elements;

/**
 * Representa um botão
 * @author Pablo Dall'Oglio
 */
class Button extends Field implements FormElementsInterface{

    private $action;
    private $label;
    private $formName;
    
    /**
     * Define a ação do botão (função a ser executada)
     * @param $action = ação do botão
     * @param $label    = rótulo do botão
     */
    public function setAction(ActionInterface $action, $label){
        $this->action = $action;
        $this->label = $label;
    }
    
    /**
     * Define o nome do formulário para a ação botão
     * @param $name = nome do formulário
     */
    public function setFormName($name){
        $this->formName = $name;
    }
    
    /**
     * exibe o botão
     */
    public function show(){
        $url = $this->action->serialize();
        
        // define as propriedades do botão
        $tag = new Elements('button');
        $tag->name    = $this->name;    // nome da TAG
        $tag->type    = 'button';       // tipo de input
        $tag->add($this->label);
        
        // define a ação do botão
        $tag->onclick = "document.{$this->formName}.action='{$url}'; ".
                                "document.{$this->formName}.submit()";
                                
        if ($this->properties){
            foreach ($this->properties as $property => $value){
                $tag->$property = $value;
            }
        }
        
        // exibe o botão
        $tag->show();
    }
}