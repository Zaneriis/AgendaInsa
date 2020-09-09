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

  public function outputicsFile($fichier){
      fputs($fichier, 'BEGIN:VCALENDAR');
      fputs($fichier, 'PRODID:-//Serveur de planning Cocktail 0.9//iCal4j 1.0');
      fputs($fichier, 'VERSION:2.0');
      fputs($fichier, 'CALSCALE:'.$this->calScale);
      fputs($fichier, 'METHOD:PUBLISH');
      fputs($fichier, 'X-WR-CALNAME:'.$this->calName);
      fputs($fichier, 'X-WR-TIMEZONE:'.$this->timeZone);
      foreach ($this->arrayEvent as $value) {
        $value->outputicsFile($fichier);
      }
      fputs($fichier, 'END:VCALENDAR');
  }

}

?>
