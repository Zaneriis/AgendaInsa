<?php

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


  function getClasseRowEvent($evenements) {
    if (empty($evenements)) {
      return "row-event-empty";
    }
    return "row-event";
  }

  function getCouleur() {
    static $compteur = 0;

    $couleurs = array("red lighten-3","pink lighten-3","purple lighten-3","deep-purple lighten-3","indigo lighten-3","blue lighten-3","teal lighten-3","green lighten-3","light-green lighten-3","lime lighten-3","yellow lighten-3","amber lighten-3","orange lighten-3","deep-orange lighten-3","brown lighten-3");

    $couleur = $couleurs[$compteur];


    $compteur++;

    if ($compteur >= sizeof($couleurs)) {
      $compteur = 0;
    }

    return $couleur;

  }

  function nombrePlagesEvenement($event) {
    $limitesHorairesEvent = array_map('trim', explode("-",$event->time));

    $debutEvent = $limitesHorairesEvent[0];
    $finEvent = $limitesHorairesEvent[1];

    return iterator_count(new DatePeriod(new DateTime($debutEvent), new DateInterval('PT15M'), new DateTime($finEvent)));

  }

  function afficherPause() {
    echo "<div class='row no-mb' style='height: 2vh'>";
    echo "</div>";
  }

  function afficherEvent($evenements) {

    echo "<div class='row no-mb' >";

    foreach ($evenements as $event) {

      $nbplages = nombrePlagesEvenement($event);
      $tailleEvenement = 2 * $nbplages ;
      echo "<div class='col-event col s".(12/(count($evenements)))." $event->couleur' style='min-height:".$tailleEvenement."vh;'>";
        echo $event->comment;
      echo "</div>";

    }

    echo "</div>";


  }


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

    function vuePrint(){
      echo '<pre>';
      echo $this->getNom();
      print_r($this->getEvenements());
      echo '</pre>';
    }

    function vue(){

        $period = new DatePeriod(new DateTime('08:00'), new DateInterval('PT15M'), new DateTime('20:00'));

        $plages = array();

        foreach ($period as $hour) {
          $plages[$hour->format("H:i")] = array();
        }

        foreach ($this->getEvenements() as $event) {
          $event->couleur = getCouleur();
          putEventInPlage($plages, $event);
        }

        include("./vue/jour.php");
    }

    function getNom(){
      return $this->label;
    }

    function getEvenements(){
      return $this->evenements;
    }

    private function loadEvent(){
      $json = file_get_contents('http://api.pacary.net/AgendaInsaRouen/index.php?fo=2020-ING-ASI-S7&ty=day&ts='.$this->getInsaTime());
      $obj = json_decode($json);
      foreach ($obj as $key => $value) {
        $this->evenement = $value;
      }

    }


  }


?>
