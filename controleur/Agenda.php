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

    public function periodeSuivant($type = "week"){
      switch ($type) {
        case 'day':
          return $this->getTime() + 3600*24;
          break;
        case 'mouth':
          return $this->getTime() + 3600*24 * intval(date("t",$this->getTime()));
          break;
        case 'year':
          return $this->getTime() + 3600*24 * (365 + intval(date("L",$this->getTime())));
          break;
        default: // week
          return $this->getTime() + 3600*24*7;
          break;
      }
    }

    public function periodePrecedente($type = "week"){
      switch ($type) {
        case 'day':
          return $this->getTime() - 3600*24;
          break;
        case 'mouth':
          return $this->getTime() - 3600*24 * intval(date("t",$this->getTime()));
          break;
        case 'year':
          return $this->getTime() - 3600*24 * (365 + intval(date("L",$this->getTime())));
          break;
        default: // week
          return $this->getTime() - 3600*24*7;
          break;
      }
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
        $i = 1;
        foreach ($this->listJours as $value) {
          echo "<div class='outer-col col s12 bordered' id='ancre_custom_".$i."'>";
          $value->vue();
          echo "</div>";
          $i++;
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


    public function getTime(){
      return $this->time;
    }
  }


?>
