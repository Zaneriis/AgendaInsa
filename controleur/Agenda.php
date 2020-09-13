<?php

require_once('Jour.php');
  /**
   *
   */
  class Agenda
  {
    private $time;
    private $type;
    private $data;
    private $listJours;
    private $session;
    private $formation;
    public function __construct($time = FALSE, $type = 'week', $session = FALSE, $formation = FALSE)
    {
      if($formation === FALSE) $formation = "2020-ING-ASI-S7";
      if($time === FALSE) $time = time();
      $this->formation = $formation;
      $this->time = $time;
      $this->type = $type;
      $this->session = $session;
      $this->listJours = array();
      $this->loadEvent();
    }

    private function loadEvent(){
      $url = 'http://api.pacary.net/AgendaInsaRouen/index.php?fo='.$this->formation.'&ty='.$this->getType().'&ts='.$this->getInsaTime();
      if($this->session !== FALSE){
        $url = $url.'&ss='.intval($this->session);
      }
      //$url = "http://51.75.253.173/AgendaInsa/index.json";
      $json = file_get_contents($url);
      $this->data = json_decode($json);


      foreach ($this->data as $key => $value) {
        array_push($this->listJours, new Jour($value,$key));
      }
    }

    private function getInsaTime(){
      return date("Ymd",$this->getTime());
    }

    public function vue(){
      $plages = getPlages();

      echo "<div class='row'>";

      if (count($this->listJours) == 1) {
        echo "<div class='col s4 offset-s4'>";
        $o = $this->listJours[0];
        $o->vue();
        echo "</div>";
      }
      else {
        foreach ($this->listJours as $value) {
          echo "<div class='outer-col col s12 bordered'>";
          $value->vue();
          echo "</div>";
        }
      }

      echo "</div>";
    }

    /*private funciton genererPeriode(){
      switch ($this->getType()) {
        // case 'year':
        //   $tab = array();
        //   break;
        // case 'mouth':
        //   $tab = array();
        //
        //   break;
        case 'day':
          return array($time);
          break;

        default:
          // code...
          break;
      }
    } */

    public function getType(){
      return $this->type;
    }

    public static function getHead(){
      echo '<!--Import Google Icon Font-->
          <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
          <!--Import materialize.css-->
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

          <link rel="stylesheet" href="vue/css/index.css">

          <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
          <!--Let browser know website is optimized for mobile-->
          <meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
    }


    public function getTime(){
      return $this->time;
    }
  }


?>
