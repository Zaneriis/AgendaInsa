<link rel="stylesheet" href="vue/css/jour.css">

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

<script src="vue/js/jour.js"></script>
