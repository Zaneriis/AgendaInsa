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
<<<<<<< HEAD
      return $this->evenement;
    }

    private function loadEvent(){
      $json = file_get_contents('http://api.pacary.net/AgendaInsaRouen/index.php?fo=2020-ING-ASI-S7&ty=day&ts='.$this->getInsaTime());
      $obj = json_decode($json);
      foreach ($obj as $key => $value) {
        $this->evenement = $value;
      }
=======
      return $this->evenements;
>>>>>>> 33278f9f9865669a9e2999070a2416d46f895c37
    }


  }


?>
