<?php

  namespace Livro\Widgets\Form;

  use Livro\Widgets\Base\Elements;

/**
 * Representa um RadioButton
 * @author Pablo Dall'Oglio
 */
class RadioButton extends Field implements FormElementsInterface
{
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        $tag = new Elements('input');
        $tag->class = 'field';		  // classe CSS
        $tag->name = $this->name;
        $tag->value = $this->value;
        $tag->type = 'radio';
        
        // se o campo não é editável
        if (!parent::getEditable())
        {
            // desabilita a TAG input
            $tag->readonly = "1";
        }
        
        if ($this->properties)
        {
            foreach ($this->properties as $property => $value)
            {
                $tag->$property = $value;
            }
        }
        
        // exibe a tag
        $tag->show();
    }
}
