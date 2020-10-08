<?php

  header('application/json');

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  include('../BDD.php');

  $agenda = htmlspecialchars($_GET['fo']);
  $bdd = BDD::load();

  $cur = $bdd->prep("SELECT identifiant, id from compte where groupe = :calendar");
  //$bdd->addParam($cur,"id",$this->session_id);
  $data = $bdd->lirePrep($cur, array("calendar"=>$agenda));

  echo json_encode($data);

?>
