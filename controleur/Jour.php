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

  function getBornesEvent($event) {
    return array_map('trim', explode("-",$event->time));
  }

  function nombrePlagesEvenement($event) {

    list($debutEvent, $finEvent) = getBornesEvent($event);

    return iterator_count(new DatePeriod(new DateTime($debutEvent), new DateInterval('PT15M'), new DateTime($finEvent)));

  }

  function getEventLePlusTot($evenements) {
    $heureLaPlusTot = strtotime("23:59:59");
    $eventLePlusTot = $evenements[0];

    foreach ($evenements as $event) {
      list($debut, $fin) = getBornesEvent($event);

      if (strtotime($debut) < $heureLaPlusTot) {
        $heureLaPlusTot = strtotime($debut);
        $eventLePlusTot = $event;
      }

    }

    return $eventLePlusTot;
  }

  function afficherPause() {
    echo "<div class='row no-mb' style='height: 2vh'>";
    echo "</div>";
  }

  function afficherPauseSiBesoin($event, $evenements) {
    $eventLePlusTot = getEventLePlusTot($evenements);
    list($debutEventTot, $finEventTot) = getBornesEvent($eventLePlusTot);
    list($debutEvent, $finEvent) = getBornesEvent($event);

    //echo "eventleplustot : $debutEventTot";
    //echo "event : $debutEvent";

    if (!($debutEventTot == $debutEvent)) {
      //echo "c pas pareil faut des pauses";

      $nbPauses = count(getPlages($heureDepart=$debutEventTot, $heureFin=$debutEvent));
      //echo "nb pauses : $nbPauses";
      for ($i=0; $i<$nbPauses; $i++) {
        afficherPause();
      }
    }

  }

  function afficherEvent($evenements) {

    echo "<div class='row no-mb' >";

    foreach ($evenements as $event) {

      if (!property_exists($event, "estAffiche") or !$event->estAffiche) {
        list ($couleurPrincipale, $couleurSecondaire) = getCouleur($event);

        afficherPauseSiBesoin($event, $evenements);

        $nbplages = nombrePlagesEvenement($event);
        $tailleEvenement = 2 * $nbplages ;
        echo "<div data-position='bottom' data-tooltip='$event->comment ($event->time)' class=' tooltipped col-event col s".(12/count($evenements))."' style='height:".$tailleEvenement."vh;'>";
          echo "<div class='row center-align $couleurSecondaire'><div class='col s12'>";
            echo "<span class='heure_de_cours'>".$event->time."</span>";
          echo "</div></div>";
          echo "<div class='row event-comment $couleurPrincipale'><div class='col s12'>";
            echo $event->comment;
          echo "</div></div>";

        echo "</div>";

        $event->estAffiche = TRUE;
      }

    }

    echo "</div>";


  }

  function getPlages($heureDepart="08:00", $heureFin="20:00", $interval="PT15M") {

    $period = new DatePeriod(new DateTime($heureDepart), new DateInterval($interval), new DateTime($heureFin));

    $plages = array();

    foreach ($period as $hour) {
      $plages[$hour->format("H:i")] = array();
    }

    return $plages;
  }

  function eventLePlusLong($evenements) {
    $periodeMax = 0;
    $eventLePlusLong = $evenements[0];

    foreach ($evenements as $event) {

      list($debutEvent, $finEvent) = getBornesEvent($event);
      $time1 = strtotime($debutEvent);
      $time2 = strtotime($finEvent);
      $difference = round(abs($time2 - $time1) / 3600,2);

      if ($difference >= $periodeMax) {
        $periodeMax = $difference;
        $eventLePlusLong = $event;
      }

    }

    return $eventLePlusLong;
  }

  function eventsConcomitants($plages, $evenements) {

    $eventLePlusLong = eventLePlusLong($evenements);

    list($heureDebut, $heureFin) = getBornesEvent($eventLePlusLong);

    $eventsTrouves = array();
    $nouvellesPlages = getPlages($heureDepart=$heureDebut, $heureFin=$heureFin);

    foreach (array_keys($nouvellesPlages) as $heureATester) {
      foreach ($plages[$heureATester] as $event) {
        if (!in_array($event, $eventsTrouves)) {
          $eventsTrouves[] = $event;
        }
      }
    }

    return $eventsTrouves;
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
            $evenements = eventsConcomitants($plages, $evenements);
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
