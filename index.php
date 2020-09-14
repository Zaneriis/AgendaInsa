<?php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once("controleur/ChoixSession.php");
require_once("controleur/Agenda.php");
require_once("controleur/GestionnaireCookie.php");
require_once("BDD.php");
$choixSession = new ChoixSession(new BDD());

$session = FALSE;
$calendar = FALSE;
$ts = time();

$gestionnaireCookie = new GestionnaireCookie($_COOKIE);
$session = $gestionnaireCookie->getSession();
$calendar = $gestionnaireCookie->getCalendrier();
if(isset($_GET['ts']) && !empty($_GET['ts'])){
  $ts = htmlspecialchars($_GET['ts']);
}
// echo $ts.'<br />';
// echo $session.'<br />';
// echo $calendar.'<br />';
?>

<html>
  <head>
    <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

        <link rel="stylesheet" href="vue/css/index.css">
        <link rel="stylesheet" href="vue/css/jour.css">

        <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <script src="vue/js/jour.js"></script>

  </head>

  <body>
        <!-- Dropdown Structure -->
    <ul id="dropdown1" class="dropdown-content">
      <?php
        $formations = $choixSession->getFormations();
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
        $agenda = new Agenda($ts,"week",$session,$calendar);
        $agenda->vue();
      ?>
    </div>

    <script src="vue/js/index.js"></script>
  </body>
</html>
