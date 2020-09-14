<?php

  /**
   *
   */
  class ChoixSession
  {
    private $arrayNomNumero;
    private $bdd;
    function __construct($bdd)
    {
      $this->bdd = $bdd;
    }

    public function getCompte($groupe = FALSE){
      $sql = "select id, identifiant from compte";
      $res;
      if($groupe !== FALSE)
      {
          $sql=$sql.' where groupe = ?';
          $cur = $this->bdd->prep($sql);
          $res = $this->bdd->lirePrep($cur,array($groupe));
      }
      else {
          $res = $this->bdd->lire($sql);
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

    public function getFormations(){
      $url = "http://api.pacary.net/AgendaInsaRouen/getAgendas.php";
      $json = file_get_contents($url);
      return json_decode($json);

    }
  }

?>
