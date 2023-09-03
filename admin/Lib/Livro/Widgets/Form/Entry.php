<?php

  namespace Livro\Widgets\Form;

  /*use Livro\Widgets\Form\Field;
  use Livro\Widgets\Form\FormElementsInterface;*/
  use Livro\Widgets\Base\Elements;

  class Entry extends Field implements FormElementsInterface{

    protected $properties;

    public function show(){
      $tag = new Elements('input');
      $tag->class = 'form-control';
      $tag->type = 'text';
      $tag->name = $this->name;
      $tag->value = $this->value;
      $tag->style = 'width:'. $this->size;

      if( !parent::getEditable() ){
        $tag->readonly = '1';
      }

      if( $this->properties ){
        foreach( $this->properties as $property => $value ){
          $tag->property = $value;
        } 
      }

      $tag->show();
    }

  }