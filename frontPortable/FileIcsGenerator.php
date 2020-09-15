<?php

/**
 *
 */
class FileIcsGenerator
{
  private $calScale;
  private $calName;
  private $timeZone;
  private $arrayEvent;

  function __construct()
  {
    $this->setCalScale('GREGORIAN');
    $this->setTimeZone('Europe/Paris');
    $this->arrayEvent = array();
  }

  public function setCalScale($data){
    $this->calScale = $data;
    return $this;
  }

  public function setCalName($data){
    $this->calName = $data;
    return $this;
  }

  public function setTimeZone($data){
    $this->timeZone = $data;
    return $this;
  }

  public function addEvent($event){
    array_push($this->arrayEvent, $event);
    return $this;
  }

  // $date = "YYYYMMDD"
  public static function proxyEvent($eventAgenda, $date){
    $o = new EventICal();
    $jour = date("Ymd",strtotime(ManipulateurDate::FrenshTextToEnglish($date)));
    $heure = $eventAgenda->time;
    $heures = explode(" - ",$heure);
    $debut = $jour.implode("",explode(":",$heures[0]))."00";
    $fin = $jour.implode("",explode(":",$heures[1]))."00";
    $o = $o->setStart($debut)->setEnd($fin)->setSummary($eventAgenda->comment)->setDescription($eventAgenda->comment);
    return $o;
  }

  public function outputicsFile($fichier){
      fputs($fichier, 'BEGIN:VCALENDAR
');
      fputs($fichier, 'PRODID:-//Serveur de Léo Pacary//
');
      fputs($fichier, 'VERSION:2.0');
      fputs($fichier, 'CALSCALE:'.$this->calScale.'
');
      fputs($fichier, 'METHOD:PUBLISH'.'
');
      fputs($fichier, 'X-WR-CALNAME:'.$this->calName.'
');
      fputs($fichier, 'X-WR-TIMEZONE:'.$this->timeZone.'
');
      foreach ($this->arrayEvent as $value) {
        $value->outputicsFile($fichier);
      }
      fputs($fichier, 'END:VCALENDAR');
  }

}

?>
