<?php
  /**
   *
   */
  class Jour
  {
    private $time;
    private $evenement;
    function __construct($time)
    {
      $this->time =  $time;
      $this->loadEvent();
    }

    function vue(){

    }

    function getNom(){
      return date('l d F', $this->getTime());
    }

    function getInsaTime(){
      return date("Ymd",$this->getTime());
    }

    function getTime(){
      return $this->time;
    }

    function getEvenements(){
      return $this->evenement;
    }

    private function loadEvent(){
      $json = file_get_contents('http://api.pacary.net/AgendaInsaRouen/index.php?fo=2020-ING-ASI-S7&ty=day&ts='.this->getInsaTime());
      $obj = json_decode($json);
      foreach ($obj as $key => $value) {
        $this->evenement = $value;
        breck;
      }
    }


  }


?>
