<?php
  header("Content-Type: application/json");
	function cprint_r($data){
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
  $type = FALSE;
  $timestamp = FALSE;
  $session_id = FALSE;
  $formation = FALSE;
  if(isset($_GET["ty"]) && !empty($_GET["ty"])){
    $type = htmlspecialchars($_GET["ty"]);
  }
  if(isset($_GET["ts"]) && !empty($_GET["ts"])){
    $timestamp = htmlspecialchars($_GET["ts"]);
  }
  if(isset($_GET["ss"]) && !empty($_GET["ss"])){
    $session_id = htmlspecialchars($_GET["ss"]);
  }
  if(isset($_GET["fo"]) && !empty($_GET["fo"])){
    $formation = htmlspecialchars($_GET["fo"]);
  }

  if(!isValidType($type)) $type = 'week';


  function isValidType($type){
    return $type == "day" || $type == "week" || $type == "mouth" || $type == "year";
  }

  function UrlGenerator($var_timestamp = FALSE, $var_type = "week", $var_formation = "2020-ING-ASI-S7"){
    if($var_timestamp === FALSE) $var_timestamp = getInsaTime();
    return 'http://agendas.insa-rouen.fr/print.php?cal='.$var_formation.'&getdate='.$var_timestamp.'&printview='.$var_type;
  }

  function getInsaTime(){
    return date('Ymd');
  }

  require('AgendaFactory.php');

  $url = UrlGenerator($timestamp,$type,$formation);
  $AF = new AgendaFactory($session_id);
  $res = $AF->createAgenda($url);
  echo json_encode($res);

?>
