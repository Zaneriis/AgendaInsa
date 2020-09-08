<?php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
  <html>
    <head>
      <!--Import Google Icon Font-->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>

    <body>

      <div class="blue container">
        <div class="row center-align">

          <h1>Agenda INSA Rouen</h1>

          <?php
          $session = FALSE;
          if(isset($_GET['ss']) && !empty($_GET['ss'])){
            $session = intval($_GET['ss']);
          }
          include('./controleur/Agenda.php');
          $agenda = new Agenda(time(),"day",$session);
          $agenda->vue();
          //$jour = new Jour(time()+4*86400);
          //include("./vue/jour.php"); ?>

        </div>
      </div>
      <!--JavaScript at end of body for optimized loading-->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    </body>
  </html>
