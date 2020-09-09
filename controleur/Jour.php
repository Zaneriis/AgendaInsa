<?php
  define("BASE_DE_DONNEES", "/bd2 /");
  define("REUNION", "/Reunion/");
  define("GESTION_STRATEGIE_FINANCE","/Gestion Strategie Finance/");
  define("TRAITEMENT_INFORMATION","/tim/");
  define("TRAITEMENT_IMAGE","/ti /");
  define("ESPAGNOL","/Espagnol /");
  define("RECHERCHE_OPERATIONELLE","/ro /");
  define("ALLEMAND","/Allemand /");
  define("ANGLAIS","/Anglais /");
  define("FRANCAISE_LANGUE_ETRANGERE","/Fle /");
  define("METHODE_GESTION_PROJET","/mgpi /");
  define("RESEAU","/ri /");

  function getCouleur($event) {



    if (preg_match(BASE_DE_DONNEES, $event->comment)) {
      $couleur = "brown";
    }
    elseif (preg_match(REUNION, $event->comment)) {
      $couleur = "pink";
    }

    elseif (preg_match(GESTION_STRATEGIE_FINANCE, $event->comment)) {
      $couleur = "purple";
    }
    elseif (preg_match(TRAITEMENT_IMAGE, $event->comment)) {
      $couleur = "amber";
    }/*
    elseif (preg_match(TRAITEMENT_INFORMATION, $event->comment)) {
      $couleur = "deep-purple";
    }
    elseif (preg_match(ESPAGNOL, $event->comment)) {
      $couleur = "indigo";
    }
    elseif (preg_match(RECHERCHE_OPERATIONELLE, $event->comment)) {
      $couleur = "blue";
    }
    elseif (preg_match(ALLEMAND, $event->comment)) {
      $couleur = "teal";
    }
    elseif (preg_match(ANGLAIS, $event->comment)) {
      $couleur = "green";
    }
    elseif (preg_match(FRANCAISE_LANGUE_ETRANGERE, $event->comment)) {
      $couleur = "light-green";
    }
    elseif (preg_match(METHODE_GESTION_PROJET, $event->comment)) {
      $couleur = "lime";
    }*/
    elseif (preg_match(RESEAU, $event->comment)) {
      $couleur = "yellow";
    }
    else {
      $couleur = couleurRandom();
    }

    return array("$couleur lighten-3", "$couleur lighten-2");

  }

  function couleurRandom() {
    static $compteur = 0;

    $couleurs = array("red","pink","purple","deep-purple","indigo","blue","teal","green","light-green","lime","yellow","amber","orange","deep-orange","brown");

    $couleur = $couleurs[$compteur];


    $compteur++;

    if ($compteur >= sizeof($couleurs)) {
      $compteur = 0;
    }

    return $couleur;
  }
  /*
  function getCouleur($event) {
    static $compteur = 0;

    $couleurs = array("red","pink","purple","deep-purple","indigo","blue","teal","green","light-green","lime","yellow","amber","orange","deep-orange","brown");

    $couleur = $couleurs[$compteur];


    $compteur++;

    if ($compteur >= sizeof($couleurs)) {
      $compteur = 0;
    }

    return array("$couleur lighten-3", "$couleur lighten-2");

  }*/
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

      list ($couleurPrincipale, $couleurSecondaire) = getCouleur($event);

      $nbplages = nombrePlagesEvenement($event);
      $tailleEvenement = 2 * $nbplages ;
      echo "<div data-position='bottom' data-tooltip='$event->comment ($event->time)' class=' tooltipped col-event col s".(12/(count($evenements)))."' style='height:".$tailleEvenement."vh;'>";
        echo "<div class='row center-align $couleurSecondaire'><div class='col s12'>";
          echo "<span class='heure_de_cours'>".$event->time."</span>";
        echo "</div></div>";
        echo "<div class='row event-comment $couleurPrincipale'><div class='col s12'>";
          echo $event->comment;
        echo "</div></div>";




      echo "</div>";

    }

    echo "</div>";


  }

  function getPlages() {

    $period = new DatePeriod(new DateTime('08:00'), new DateInterval('PT15M'), new DateTime('20:00'));

    $plages = array();

    foreach ($period as $hour) {
      $plages[$hour->format("H:i")] = array();
    }

    return $plages;
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
        $plages = getPlages();

        foreach ($this->getEvenements() as $event) {
          putEventInPlage($plages, $event);
        }

        echo "<div class='row center-align bordered titre-jour'>";
        echo $this->getNom();
        echo "</div>";

        $previousEvents = NULL;

        foreach ($plages as $heure => $evenements) {

          if ((is_null($previousEvents) or !($evenements == $previousEvents)) and !(empty($evenements))) {
            afficherEvent($evenements);
          }
          elseif (empty($evenements)){
            afficherPause();
          }

          $previousEvents = $plages[$heure];

        }
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
