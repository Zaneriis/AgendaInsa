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

  $period = new DatePeriod(new DateTime('08:00'), new DateInterval('PT15M'), new DateTime('20:00'));

  $plages = array();

  foreach ($period as $hour) {
    $plages[$hour->format("H:i")] = array();
  }

  foreach ($this->getEvenements() as $event) {
    $event->couleur = getCouleur();
    putEventInPlage($plages, $event);
  }
?>

<link rel="stylesheet" href="vue/css/jour.css">

<div class="row row-jour-title">
  <div class="col">
    <h4><?php $this->getNom(); ?></h4>
  </div>
</div>

<div class="row row-planning">
  <div class="col s1">
      <?php

        foreach ($plages as $hour => $evenements) {
          echo "<div class='row left-align row-heure ".(getClasseRowEvent($evenements))."'>";
            echo $hour;
          echo "</div>";
        }
      ?>
  </div>

  <div class="col s11">
      <?php
      $previousPlage = NULL;

      foreach ($plages as $heure => $evenements) {

        echo "<div class='row ".(getClasseRowEvent($evenements))."'>";
        foreach ($evenements as $event) {
          echo "<div class='col-event col s".(12/(count($evenements)))." ".($event->couleur)."'>";

          if (!is_null($previousPlage)) {
            if (!in_array($event, $previousPlage)) {
                echo $event->comment;
            }
          }
          else {
            echo $event->comment;
          }

          echo "</div>";
        }
        echo "</div>";

        $previousPlage = $plages[$heure];
      }

      ?>
  </div>
</div>
