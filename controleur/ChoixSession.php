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
    }

    public function getCompte($groupe = FALSE){
      $sql = "select id, identifiant from compte";
      $bdd = new BDD();
      $res;
      if($groupe !== FALSE)
      {
          $sql=$sql.' where groupe = ?';
          $cur = $bdd->prep($sql);
          $res = $bdd->lirePrep($cur,array($groupe));
      }
      else {
          $res = $bdd->lire($sql);
      }
      $arrayReturn = array();
      foreach ($res as $value) {
        $arrayReturn[$value['identifiant']." - ".$value['id']] = 'null';
      }
      return $arrayReturn;
    }

    private function vue(){
      print_r($this->getData());
    }

    public function getData(){
      return $this->arrayNomNumero;
    }
  }

?>
