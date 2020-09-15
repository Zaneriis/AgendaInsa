

<?php

require_once('FileIcsGenerator.php');
require_once('EventICal.php');
require_once('ManipulateurDate.php');
if(isset($_GET['fo']) && !empty($_GET['fo'])){
  $calName = htmlspecialchars($_GET['fo']);
  $url = "http://api.pacary.net/AgendaInsaRouen/index.php?fo=".$calName;
  if(isset($_GET['ss']) && !empty($_GET['ss'])){
    $session = intval($_GET['ss']);
    $url .= "&ss=".$session;
  }
  $json = file_get_contents($url);
  $data = json_decode($json);
  if($data == NULL) exit(0);
  $a = new FileIcsGenerator();
  $a->setCalName($calName);
  foreach ($data as $key => $value) {
    foreach ($value as $subkey => $subvalue) {
      $a->addEvent(FileIcsGenerator::proxyEvent($subvalue,$subkey));
    }
  }
  $monfichier = fopen("ics/".$calName.'.ics', 'w');
  $a->outputicsFile($monfichier);
  header('Location:ics/'.$calName.'.ics');
}

?>
