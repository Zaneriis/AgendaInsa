<link rel="stylesheet" href="vue/css/jour.css">

<div class="row row-jour-title">
  <div class="col">
    <h4><?php $this->getNom(); ?></h4>
  </div>
</div>

<div class="row row-planning">
  <div class="col s1">
      <?php

        foreach ($plages as $heure => $evenements) {
          echo "<div class='row' style='height: 2vh;'>";
          echo "<span class='horaire'>".$heure."</span>";
          echo "</div>";
        }
      ?>
  </div>

  <div class="col s11">
      <?php

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





        /*
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

        $previousPlage = $plages[$heure];*/


      ?>
  </div>
</div>
