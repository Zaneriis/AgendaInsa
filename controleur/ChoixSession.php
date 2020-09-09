<?php

  /**
   *
   */
  include('BDD.php');
  class ChoixSession
  {
    private $arrayNomNumero;
    function __construct()
    {
      $this->loadData();
    }

    private function loadData(){
      $bdd = new BDD();
      $req = 'SELECT id, username FROM compte';
      $this->arrayNomNumero = $bdd->lire($req);
    }

    private function vue(){
      print_r($this->getData());
    }

    public function getData(){
      return $this->arrayNomNumero;
    }
  }

?>
