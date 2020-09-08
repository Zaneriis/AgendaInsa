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


  $period = new DatePeriod(new DateTime('08:00'), new DateInterval('PT15M'), new DateTime('20:00'));

  $plages = array();

  foreach ($period as $hour) {
    $plages[$hour->format("H:i")] = array();
  }

  foreach ($this->getEvenements() as $event) {
    putEventInPlage($plages, $event);
  }

?>

<link rel="stylesheet" href="vue/css/jour.css">

<div class="row row-jour yellow">
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
          echo "<div class='col-event col s".(12/(count($evenements)))."'>";

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
