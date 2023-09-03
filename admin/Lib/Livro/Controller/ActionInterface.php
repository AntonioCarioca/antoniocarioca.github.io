<?php

  namespace Livro\Controller;

  interface ActionInterface {
    public function setParameter($param, $value);
    public function serialize();
}