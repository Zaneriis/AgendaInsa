<?php

  header('application/json');

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  include('ChoixSession.php');
  include('../BDD.php');
  $choixSession = new ChoixSession(new BDD());

  $json = $choixSession->getFormations();

  //print_r( $choixSession->getFormations());
  echo json_encode($choixSession->getFormations());

?>
