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


if(isset($_GET['ts']) && !empty($_GET['ts'])){
  $ts = htmlspecialchars($_GET['ts']);
}
else {
  $ts = time();
}


$gestionnaireCookie = new GestionnaireCookie($_COOKIE);
$session = $gestionnaireCookie->getSession();
$calendar = $gestionnaireCookie->getCalendrier();
//$session = 36;
$agenda = new Agenda($ts-86000,"week",$session,$calendar);

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

        <link href="vue/css/hover.css" rel="stylesheet" media="all">
        <link rel="stylesheet" href="vue/css/index.css">
        <link rel="stylesheet" href="vue/css/jour.css">

        <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <script src="vue/js/jour.js"></script>

  </head>

  <body>

    <!-- barre navigation normale -->
    <nav>
      <div class="nav-wrapper">
        <a href="#!" class=" brand-logo">Insa Rouen</a>

        <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>

        <ul class="right hide-on-med-and-down">
          <!-- Dropdown Trigger -->
          <li><a href="?ts=<?php echo $agenda->periodePrecedente("week")?>" data-position="bottom" data-tooltip="Semaine précédente" class="tooltipped btn-floating waves-effect waves-light red"><i class="material-icons">fast_rewind</i></a></li>
          <li><a href="?" data-position="bottom" data-tooltip="Aujourd'hui" class="tooltipped btn-floating waves-effect waves-light red"><i class="material-icons">today</i></a></li>
          <li><a href="?ts=<?php echo $agenda->periodeSuivant("week")?>" data-position="bottom" data-tooltip="Semaine suivante"  class="tooltipped btn-floating waves-effect waves-light red"><i class="material-icons">fast_forward</i></a></li>

          <li><a class=" waves-effect waves-light btn modal-trigger" href="#modal1">Choisir ma formation</a></li>

          <?php
            if (isset($_GET["fo"])) {
              if ($_GET["fo"] == "2020-ING-ASI-S7" || $_GET["fo"] == "2020-ING-ASI-S8") {
                echo '<li><a class="waves-effect waves-light btn modal-trigger" href="#modal2">Mon emploi du temps</a></li>';
              }
            }
          ?>

        </ul>
      </div>
    </nav>

    <div id="modal1" class="modal">
      <div class="modal-content" style="height: 50vh;">
        <h4>Choisir ma formation</h4>
        <div class="row">
          <div class="input-field col s12">
            <i class="material-icons prefix">textsms</i>
            <input type="text" id="autocomplete-input-formation" class="autocomplete" autocomplete="off">
            <label for="autocomplete-input-formation">Entrez le nom de votre formation</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#!" id="validation-formation" class="modal-close waves-effect waves-green btn-flat">Valider</a>
      </div>
    </div>

    <div id="modal2" class="modal">
      <div class="modal-content" style="height: 50vh;">
        <h4>Sélectionnez votre session grâce à votre nom</h4>
        <div class="row">
          <div class="input-field col s12">
            <i class="material-icons prefix">textsms</i>
            <input type="text" id="autocomplete-input-session" class="autocomplete" autocomplete="off">
            <label for="autocomplete-input-session">Entrez votre nom</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#!" id="validation-session" class="modal-close waves-effect waves-green btn-flat">Valider</a>
      </div>
    </div>

    <!-- menu quand fenetre raccourcie -->
    <ul class="sidenav center-align " id="mobile-demo">
      <li><a class="waves-effect btn waves-light modal-trigger" href="#modal1">Choisir ma formation</a></li>
      <?php
        if (isset($_GET["fo"])) {
          if ($_GET["fo"] == "2020-ING-ASI-S7" || $_GET["fo"] == "2020-ING-ASI-S8") {
            echo '<li><a class="waves-effect waves-light btn modal-trigger" href="#modal2">Mon emploi du temps</a></li>';
          }
        }
      ?>
    </ul>

    <div class="container">
      <?php
        $agenda->vue();
      ?>
    </div>

    <a class="btn-floating agenda-operations-floating tooltipped" data-position="top" data-tooltip="Jour suivant" id="puce" href="#ancre_custom_2"><i class="material-icons">arrow_downward</i></a>
    <a id="semaine_precedente" href="?ts=<?php echo $agenda->periodePrecedente("week")?>" data-position="top" data-tooltip="Semaine précédente" class="tooltipped agenda-operations-floating btn-floating waves-effect waves-light red"><i class="material-icons">fast_rewind</i></a>
    <a id="semaine_suivante" href="?ts=<?php echo $agenda->periodeSuivant("week")?>" data-position="top" data-tooltip="Semaine suivante" class="tooltipped agenda-operations-floating btn-floating waves-effect waves-light red"><i class="material-icons">fast_forward</i></a>

    <script src="vue/js/index.js"></script>
    <script src="vue/js/buttonScroll.js"></script>
  </body>

</html>
