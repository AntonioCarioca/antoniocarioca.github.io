<?php

  namespace Livro\Widgets\Form;

  interface FormElementsInterface{

    public function setName($name);
    public function getName();
    public function setValue($value);
    public function getValue();
    public function show();

  }