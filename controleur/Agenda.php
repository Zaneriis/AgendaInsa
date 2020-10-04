<?php

  /* FAUT MODIFIER CA PCK LA TAILLE DES PLAGES EST DANS JOUR.PHP ET CEST 2 METHODES QUI
  PERMETTENT DAVOIR LE GRAND TABLEAU AVEC LES EVENEMENTS TRIES PAR PLAGE HORAIRE */


  function putEventInPlage(&$tableauPlages, $event) {
    $limitesHorairesEvent = array_map('trim', explode("-",$event->time));

    $debutEvent = $limitesHorairesEvent[0];
    $finEvent = $limitesHorairesEvent[1];

    $periodEvent = new DatePeriod(new DateTime($debutEvent), new DateInterval('PT15M'), new DateTime($finEvent));

    foreach($periodEvent as $hour) {
      $hourFormatted = $hour->format("H:i");

      array_push($tableauPlages[$hourFormatted], $event);
    }
  }

  function getPlages($heureDepart=NULL, $heureFin=NULL, $interval=NULL) {

    if (is_null($heureDepart)) {
      $heureDepart = new DateTime("08:00");
    }
    if (is_null($heureFin)) {
      $heureFin = new DateTime("20:00");
    }
    if (is_null($interval)) {
      $interval = new DateInterval("PT15M");
    }

    $period = new DatePeriod($heureDepart, $interval, $heureFin);

    $plages = array();

    foreach ($period as $hour) {
      $plages[$hour->format("H:i")] = array();
    }

    return $plages;
  }

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
    private $filtre_cache;
    public function __construct($time = FALSE, $type = 'week', $session = FALSE, $formation = FALSE)
    {
      if($formation === FALSE) $formation = "2020-ING-ASI-S7";
      if($time === FALSE) $time = time();
      $this->formation = $formation;
      $this->time = $time;
      $this->type = $type;
      $this->session = $session;
      $this->listJours = array();
      $this->filtre_cache = $this->getFilter($session);
      //print_r($session);
      //print_r($this->filtre_cache);
      $this->loadEvent();
    }

    public function getSessionPossible(){
        $bdd = BDD::load();
        $cur = $bdd->prep("SELECT identifiant, id from compte where groupe = :calendar");
        //$bdd->addParam($cur,"id",$this->session_id);
        return $bdd->lirePrep($cur, array("calendar"=>$this->formation));
    }


    private function getFilter($session_id){
      $bdd = BDD::load();
      $cur = $bdd->prep("SELECT * from filtres where id_session = :id and Actif = 1;");
      //$bdd->addParam($cur,"id",$this->session_id);
      return $bdd->lirePrep($cur, array("id"=>$session_id));
    }

    private function checkFiltre($str){
      foreach ($this->filtre_cache as $value) {
        if(!$this->checkOneFiltre($str,$value['pattern'])) return false;
      }
      return true;
    }

      private function checkOneFiltre($str,$pattern)
      {
        return strpos($str->comment,$pattern) === FALSE;
      }

    private function loadEvent(){
      $url = 'http://api.pacary.net/AgendaInsaRouen/index.php?fo='.$this->formation.'&ty='.$this->getType().'&ts='.$this->getInsaTime();
      // if($this->session !== FALSE){
      //   $url = $url.'&ss='.intval($this->session);
      // }
      //$url = "http://51.75.253.173/AgendaInsa/index.json";
      $json = file_get_contents($url);
      $this->data = json_decode($json);


      foreach ($this->data as $key => $value) {
        $data_jour = array();
        foreach ($value as $subvalue) {
          if($this->checkFiltre($subvalue)) array_push($data_jour,$subvalue);
        }
        array_push($this->listJours, new Jour($data_jour,$key));
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
          echo "<div class='outer-col col s12 ' id='ancre_custom_".$i."'>";
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
