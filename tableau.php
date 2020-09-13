<link rel="stylesheet" href="vue/css/jour.css">

<?php
  $session = FALSE;
  $formation = FALSE;
  if(isset($_GET['ss']) && !empty($_GET['ss'])){
    $session = intval($_GET['ss']);
  }
  if(isset($_GET['fo']) && !empty($_GET['fo'])){
    $formation = htmlspecialchars($_GET['fo']);
  }
  require_once('./controleur/Agenda.php');
  $agenda = new Agenda(time()+84000,"week",$session,$formation);
  $agenda->vue();
  //$jour = new Jour(time()+4*86400);
  //include("./vue/jour.php");
?>

<script src="vue/js/jour.js"></script>
