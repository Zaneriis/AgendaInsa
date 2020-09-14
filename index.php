<?php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once("controleur/ChoixSession.php");
require_once("controleur/Agenda.php");
require_once("BDD.php");
$session = new ChoixSession(new BDD());
?>

<html>
  <head>
    <?php
      Agenda::getHead();
    ?>

  </head>

  <body>
        <!-- Dropdown Structure -->
    <ul id="dropdown1" class="dropdown-content">
      <?php

        $formations = $session->getFormations();
        foreach ($formations as $formation) {
          echo "<li><a href='#!'>$formation</a></li>";
        }
      ?>
    </ul>

    <!-- barre navigation normale -->
    <nav>
      <div class="nav-wrapper">
        <a href="#!" class="brand-logo">Agenda Insa Rouen - made in Dr.Dévé</a>
        <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
          <!-- Dropdown Trigger -->
          <li><a class="dropdown-trigger" href="#!" data-target="dropdown1">Formation<i class="material-icons right">arrow_drop_down</i></a></li>
        </ul>
      </div>
    </nav>

    <!-- menu quand fenetre raccourcie -->
    <ul class="sidenav" id="mobile-demo">
      <li><a href="sass.html">Sass</a></li>
      <li><a href="badges.html">Components</a></li>
      <li><a href="collapsible.html">Javascript</a></li>
      <li><a href="mobile.html">Mobile</a></li>
    </ul>

    <div class="container">
      <?php
        $session = FALSE;
        $formation = FALSE;
        $ts = time();
        if(isset($_GET['ss']) && !empty($_GET['ss'])){
          $session = intval($_GET['ss']);
        }
        if(isset($_GET['fo']) && !empty($_GET['fo'])){
          $formation = htmlspecialchars($_GET['fo']);
        }
        if(isset($_GET['ts']) && !empty($_GET['ts'])){
          $ts = htmlspecialchars($_GET['ts']);
        }
        $agenda = new Agenda($ts,"week",$session,$formation);
        $agenda->vue();
      ?>
    </div>

    <script src="vue/js/index.js"></script>




  </body>
</html>
