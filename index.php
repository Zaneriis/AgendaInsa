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
      <link rel="stylesheet" href="vue/css/index.css">
      <link rel="stylesheet" href="vue/css/jour.css">

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>

    <body class="grey lighten-2">

      <div class="container white">

          <?php
          $session = FALSE;
          if(isset($_GET['ss']) && !empty($_GET['ss'])){
            $session = intval($_GET['ss']);
          }
          if(isset($_GET['fo']) && !empty($_GET['fo'])){
            $formation = htmlspecialchars($_GET['fo']);
          }
          include('./controleur/Agenda.php');
          $agenda = new Agenda(time(),"week",$session,$formation);
          $agenda->vue();
          //$jour = new Jour(time()+4*86400);
          //include("./vue/jour.php"); ?>

      </div>
      <!--JavaScript at end of body for optimized loading-->
      <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

      <script src="vue/js/jour.js"></script>
    </body>
  </html>
