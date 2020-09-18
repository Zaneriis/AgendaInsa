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

    list($debutEvent, $finEvent) = getBornesEvent($event);

    return iterator_count(new DatePeriod(new DateTime($debutEvent), new DateInterval('PT15M'), new DateTime($finEvent)));

  }



  function afficherPause() {
    echo "<div class='row no-mb' style='height: 2vh'>";
    echo "</div>";
  }

  function getNombrePlagesDecalage($event, $evenements) {
    //pour calculer les pauses en fonction du décalage entre le début de l'évenement (1er paramètre) et le début
    //du cours le plus tot dans la liste des évènements (2nd paramètre)

    $eventLePlusTot = getEventLePlusTot($evenements);
    list($debutEventTot, $finEventTot) = getBornesEvent($eventLePlusTot);
    list($debutEvent, $finEvent) = getBornesEvent($event);

    //echo "eventleplustot : $debutEventTot";
    //echo "event : $debutEvent";

    if (!($debutEventTot == $debutEvent)) {
      //echo "c pas pareil faut des pauses";
      return count(getPlages($heureDepart=$debutEventTot, $heureFin=$debutEvent));
      //echo "nb pauses : $nbPauses";


      /*
      for ($i=0; $i<$nbPauses; $i++) {
        afficherPause();
      }*/

    }

    return 0;

  }

  function afficherEvent($evenements) {

    echo "<div class='row no-mb' >";

    foreach ($evenements as $event) {



      if (!property_exists($event, "estAffiche") or !$event->estAffiche) {
        list ($couleurPrincipale, $couleurSecondaire) = getCouleur($event);

        if (count($evenements) == 5) { //division pas entiere avec 5 pose probleme quand il y a 5 cours en meme temps
          $nbColonnes = 2;
        } else {
          $nbColonnes = (12/count($evenements));
        }

        $nombrePlagesDecalage = getNombrePlagesDecalage($event, $evenements);



        $nbplages = nombrePlagesEvenement($event);
        $tailleEvenement = 2 * ($nbplages + $nombrePlagesDecalage);

        echo "<div class='col-event col s$nbColonnes ' style='height:".$tailleEvenement."vh;'>";

          if ($nombrePlagesDecalage != 0) {
            echo "<div class='row'><div class='col s12'>";
            for ($i=0; $i<$nombrePlagesDecalage; $i++) {
              afficherPause();
            }
            echo "</div></div>";
          }

          echo "<div class='row'><div data-position='top' data-tooltip='$event->comment ($event->time)'  class='col s12 tooltipped' style='height: ".(2*$nbplages)."vh;'>";



            echo "<div class='row center-align $couleurSecondaire'><div class='col s12'";
              echo "<span class='heure_de_cours'>".$event->time."</span>";
            echo "</div></div>";

            echo "<div class='row $couleurPrincipale'><div class='col s12 event-comment'>";
              echo $event->comment;
            echo "</div></div>";

          echo "</div></div>";


        echo "</div>";

        $event->estAffiche = TRUE;
      }

    }

    echo "</div>";


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
  /**
   *
   */
  class Jour
  {

    private $heureDebutPremierePlage;
    private $heureDebutDernierePlage;
    private $tempsPlage;
    private $hauteurPlageValeur;
    private $hauterPlageUnite;

    private $label;
    private $evenements;

    function __construct($data, $label)
    {
      $this->heureDebutPremierePlage = new DateTime("08:00");
      $this->heureDebutDernierePlage = new DateTime("20:00");
      $this->tempsPlage = new DateInterval("PT15M");
      $this->hauteurPlageValeur = 2;
      $this->hauteurPlageUnite = "vh";

      $this->label =  $label;
      $this->evenements = $data;
    }

    function getBornesEvent($event) {
      $bornes = array_map('trim', explode("-",$event->time));
      return array(new DateTime($bornes[0]), new DateTime($bornes[1]));
    }

    function getTaillePlage($multiplicite) {
      return ($multiplicite*$this->hauteurPlageValeur).$this->hauteurPlageUnite;
    }

    function getEventLePlusTot($evenements) {
      $heureLaPlusTot = new DateTime("23:59:59");
      $eventLePlusTot = $evenements[0];

      foreach ($evenements as $event) {
        list($debut, $fin) = $this->getBornesEvent($event);

        if ($debut < $heureLaPlusTot) {
          $heureLaPlusTot = $debut;
          $eventLePlusTot = $event;
        }

      }
      return $eventLePlusTot;
    }

    function getEventLePlusTard($evenements) {
      $heureLaPlusTard = new DateTime("00:00:01");
      $eventLePlusTard = $evenements[0];

      foreach ($evenements as $event) {
        list($debut, $fin) = $this->getBornesEvent($event);

        if ($fin > $heureLaPlusTard) {
          $heureLaPlusTard = $fin;
          $eventLePlusTard = $event;
        }
      }
      return $eventLePlusTard;
    }

    function vuePrint(){
      echo '<pre>';
      echo $this->getNom();
      print_r($this->getEvenements());
      echo '</pre>';
    }

    function coursAffichables($listeCours) {
        foreach ($listeCours as $cours) {
          if (!$cours->estAffiche) {
            return TRUE;
          }
        }
        return FALSE;
    }

    function plageAffichable($prochainePlage, $plages) {
        if ($this->coursAffichables($plages[$prochainePlage->format("H:i")]) || empty($plages[$prochainePlage->format("H:i")])) {
          return TRUE;
        }
        return FALSE;

    }

    function afficherPause($prochainePlage) {
        echo "<div class='row' style='height: ".$this->getTaillePlage(1).";'><span>Pause</span></div>";
        return $prochainePlage->add($this->tempsPlage);
    }

    function getBlocEvenements($prochainePlage, $plages) {

        $tousLesEvents = $plages[$prochainePlage->format("H:i")];

        while ($prochainePlage < $this->getBornesEvent($this->getEventLePlusTard($tousLesEvents))[1]) {

          foreach ($plages[$prochainePlage->format("H:i")] as $event) {
            if (!in_array($event, $tousLesEvents)) {
                array_push($tousLesEvents, $event);
            }
          }

          $prochainePlage->add($this->tempsPlage);
        }

        return $tousLesEvents;
    }

    function afficherBlocCours($prochainePlage, $plages) {
      $evenementsDuBloc = $this->getBlocEvenements($prochainePlage, $plages);

      echo "<br>--------";
      foreach ($evenementsDuBloc as $event) {
        $event->estAffiche = TRUE;
        echo "<br>".$event->comment." ".($event->estAffiche ? 'true' : 'false');

      }
      echo "<br>---------";

      return $this->getBornesEvent($this->getEventLePlusTard($evenementsDuBloc))[1];
    }

    function afficherHeader() {

        list($heureLaPlusTot, $osef) = $this->getBornesEvent($this->getEventLePlusTot($this->getEvenements()));
        list($osef, $heureLaPlusTard) = $this->getBornesEvent($this->getEventLePlusTard($this->getEvenements()));

        echo "<div class='row center-align bordered titre-jour'>";
        echo $this->getNom()." <strong>(".$heureLaPlusTot->format("H:i")." - ".$heureLaPlusTard->format("H:i").")</strong>";
        echo "</div>";
    }

    function afficherPlanning($plages) {
      $prochainePlage = $this->heureDebutPremierePlage;

      do {

        if ($this->plageAffichable($prochainePlage, $plages)) {
          //echo "<br>affichage de la plage : ".$prochainePlage->format("H:i");

          /*
          echo "<pre>";
          //print_r($plages);

          //print_r($plages[$prochainePlage->format("H:i")]);
          echo "</pre>";*/

          if(empty($plages[$prochainePlage->format("H:i")])) {
            $prochainePlage = $this->afficherPause($prochainePlage);
          }
          else {
            $prochainePlage = $this->afficherBlocCours($prochainePlage, $plages);
          }

        }
        else {
          $prochainePlage->add($this->tempsPlage);
        }

      } while($prochainePlage < $this->heureDebutDernierePlage);
    }

    function vue(){
        $plages = getPlages();

        foreach ($this->getEvenements() as $event) {
          $event->estAffiche = FALSE;
          putEventInPlage($plages, $event);
        }

        $this->afficherHeader();
        $this->afficherPlanning($plages);
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
