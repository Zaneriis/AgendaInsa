<?php

  /**
   *
   */
  class Jour
  {

    const BASE_DE_DONNEES = "/bd2 /";
    const REUNION = "/Reunion/";
    const GESTION_STRATEGIE_FINANCE = "/Gestion Strategie Finance/";
    const TRAITEMENT_INFORMATION = "/tim/";
    const TRAITEMENT_IMAGE = "/ti /";
    const ESPAGNOL = "/Espagnol /";
    const RECHERCHE_OPERATIONELLE = "/ro /";
    const ALLEMAND = "/Allemand /";
    const ANGLAIS = "/Anglais /";
    const FRANCAISE_LANGUE_ETRANGERE = "/Fle /";
    const METHODE_GESTION_PROJET = "/mgpi /";
    const RESEAU = "/ri /";
    const GP = "/Gestion de Projet/";
    const IML = "/iml /";

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

    function getCouleur($event) {

      if (preg_match(self::BASE_DE_DONNEES, $event->comment)) {
        $couleur = "brown";
      }
      elseif (preg_match(self::REUNION, $event->comment)) {
        $couleur = "pink";
      }

      elseif (preg_match(self::GESTION_STRATEGIE_FINANCE, $event->comment)) {
        $couleur = "purple";
      }
      elseif (preg_match(self::TRAITEMENT_IMAGE, $event->comment)) {
        $couleur = "amber";
      }
      elseif (preg_match(self::TRAITEMENT_INFORMATION, $event->comment)) {
        $couleur = "deep-purple";
      }
      elseif (preg_match(self::ESPAGNOL, $event->comment)) {
        $couleur = "indigo";
      }
      elseif (preg_match(self::RECHERCHE_OPERATIONELLE, $event->comment)) {
        $couleur = "blue";
      }
      elseif (preg_match(self::ALLEMAND, $event->comment)) {
        $couleur = "teal";
      }
      elseif (preg_match(self::ANGLAIS, $event->comment)) {
        $couleur = "green";
      }
      elseif (preg_match(self::FRANCAISE_LANGUE_ETRANGERE, $event->comment)) {
        $couleur = "light-green";
      }
      elseif (preg_match(self::METHODE_GESTION_PROJET, $event->comment)) {
        $couleur = "lime";
      }
      elseif (preg_match(self::RESEAU, $event->comment)) {
        $couleur = "yellow";
      }
      elseif (preg_match(self::IML, $event->comment)) {
        $couleur = "orange";
      }
      elseif (preg_match(self::GP, $event->comment)) {
        $couleur = "deep-orange";
      }      else {
        $couleur = $this->couleurRandom();
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

    function getNombrePlages($event) {
      list($debutEvent, $finEvent) = $this->getBornesEvent($event);
      return iterator_count(new DatePeriod($debutEvent, $this->tempsPlage, $finEvent));
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
        echo "<div class='row' style='overflow: hidden; height: ".$this->getTaillePlage(1).";'></div>";
        $prochainePlage->add($this->tempsPlage);
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

    function insererEvent(&$tableauDeTableaux, $event) {

      list($hDebut, $hFin) = $this->getBornesEvent($event);

      foreach ($tableauDeTableaux as $index => $colonne) {

        list($hDebutTard, $hFinTard) = $this->getBornesEvent($this->getEventLePlusTard($colonne));

        if($hDebut > $hFinTard) { // peut etre >= je sais pas
          /*echo "---- ajout de <br>$event->comment<br>";
          echo "<br> à <br>";
          echo "<pre>";
          print_r($tableauDeTableaux);
          echo "</pre>";
          echo "<br>-----";*/
          array_push($tableauDeTableaux[$index], $event);

          /*echo "<br>----- résultat après insertion";
          echo "<pre>";
          print_r($tableauDeTableaux);
          echo "</pre>";
          echo "<br>-----";
          */
          return TRUE;
        }
      }

      return FALSE;
    }

    function trierEvenementsBloc($evenements) {
      $tableauDeTableaux = array();

      foreach ($evenements as $event) {

        $insertionReussie = $this->insererEvent($tableauDeTableaux, $event);

        if (!$insertionReussie) {
          $nouveauTableau = array($event);
          array_push($tableauDeTableaux, $nouveauTableau);
        }
      }

      return $tableauDeTableaux;
    }

    function afficherBlocCours($prochainePlage, $plages) {
      $evenementsDuBloc = $this->getBlocEvenements($prochainePlage, $plages);
      $colonnes = $this->trierEvenementsBloc($evenementsDuBloc);

      echo "<div class='row' style='display: flex'>";
      foreach ($colonnes as $nbColonne => $colonne) {

        echo "<div class='column' style='overflow: hidden; width: ".(100/count($colonnes))."%;'>";

        $FinDernierCours = NULL;
        foreach ($colonne as $event) {

          list($debutEvent, $finEvent) = $this->getBornesEvent($event);
          list($couleurPrincipale, $couleurSecondaire) = $this->getCouleur($event);

          while(!is_null($FinDernierCours) && $debutEvent > $FinDernierCours) {
            $FinDernierCours = $this->afficherPause($FinDernierCours); //argument change rien
          }

          echo "<style type='text/css'>

            .hvr-sweep-to-bottom:before {
              background : rgba(255,255,255,0.2);
            }

            .hvr-sweep-to-bottom:hover {
              color: black;
            }
          </style>";

          echo "<div data-position='bottom' data-tooltip='$event->comment' class='tooltipped row col-event  $couleurPrincipale hvr-sweep-to-bottom' style='height: ".($this->getTaillePlage($this->getNombrePlages($event))).";'><div class=' col s12'>";
          echo "<div class='row center-align $couleurSecondaire'>";
          echo "$event->time";
          echo "</div>";
          echo "<div class='row'>";
          echo "<span> $event->comment </span>";
          echo "</div>";
          echo "</div></div>";

          $FinDernierCours = $finEvent;

          $event->estAffiche = TRUE;

        }
        echo "</div>";

      }
      echo "</div>";

      while($prochainePlage < $this->getBornesEvent($this->getEventLePlusTard($evenementsDuBloc))[1]) {
        $prochainePlage->add($this->tempsPlage);
      }
    }

    function afficherHeader() {

        list($heureLaPlusTot, $osef) = $this->getBornesEvent($this->getEventLePlusTot($this->getEvenements()));
        list($osef, $heureLaPlusTard) = $this->getBornesEvent($this->getEventLePlusTard($this->getEvenements()));

        echo '<div class="row center-align bordered titre-jour">';
        echo $this->getNom()." <strong>(".$heureLaPlusTot->format("H:i")." - ".$heureLaPlusTard->format("H:i").")</strong>";
        echo "</div>";
    }

    function afficherPlanning($plages) {
      $prochainePlage = $this->heureDebutPremierePlage;

      do {

        if ($this->plageAffichable($prochainePlage, $plages)) {

          if(empty($plages[$prochainePlage->format("H:i")])) {
            $this->afficherPause($prochainePlage);
          }
          else {
            $this->afficherBlocCours($prochainePlage, $plages);
          }

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
