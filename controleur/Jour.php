<?php
  /**
   *
   */
  class Jour
  {
    private $label;
    private $evenements;
    function __construct($data, $label)
    {
      $this->label =  $label;
      $this->evenements = $data;
    }

    function vue(){
      echo '<pre>';
      echo $this->getNom();
      print_r($this->getEvenements());
      echo '</pre>';
    }

    function getNom(){
      return $this->label;
    }

    function getEvenements(){
      return $this->evenements;
    }


  }


?>
