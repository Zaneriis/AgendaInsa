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
    public function __construct($time = FALSE, $type = 'week', $session = FALSE)
    {
      if($time === FALSE) $time = time();
      $this->time = $time;
      $this->type = $type;
      $this->session = $session;
      $this->listJours = array();
      $this->loadEvent();
    }

    private function loadEvent(){
      $url = 'http://api.pacary.net/AgendaInsaRouen/index.php?fo=2020-ING-ASI-S7&ty='.$this->getType().'&ts='.$this->getInsaTime();
      if($this->session !== FALSE){
        $url = $url.'&ss='.intval($this->session);
      }
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
      foreach ($this->listJours as $value) {
        $value->vue();
      }
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