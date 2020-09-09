<?php

function FrStringToTimeStamp($str){
  $o = DateTime::createFromFormat ("YmdHis", $str, new DateTimeZone('Europe/Paris') );
  return $o->getTimeStamp();
}

/**
 *
 */
class EventICal
{
  private $dtStart;
  private $dtEnd;
  private $summary;
  private $description;
  function __construct()
  {

  }

  public function setStart($data){
    $this->dtStart = FrStringToTimeStamp($data);
    echo $data;
    echo '<br />';
    return $this;
  }
  public function setEnd($data){
    $this->dtEnd = FrStringToTimeStamp($data);
    return $this;
  }
  public function setSummary($data){
    $this->summary = $data;
    return $this;
  }
  public function setDescription($data){
    $this->description = $data;
    return $this;
  }

  public function outputicsFile($fichier){
    date_default_timezone_set('UTC');
    fputs($fichier, 'BEGIN:VEVENT'.'
');
    fputs($fichier, 'DTSTART:'.date('Ymd\This',$this->dtStart).'Z
');
    fputs($fichier, 'DTEND:'.date('Ymd\This',$this->dtEnd).'Z
');
echo 'DTEND:'.date('Ymd\This',$this->dtEnd).'Z
';
echo 'DTSTART:'.date('Ymd\This',$this->dtStart).'Z
';
    fputs($fichier, 'SUMMARY:'.$this->summary.'
');
    fputs($fichier, 'DESCRIPTION:'.$this->description.'
');
    fputs($fichier, 'END:VEVENT'.'
');
    // echo $this->dtStart;
    // echo $this->dtEnd;
    // echo $this->summary;
    // echo $this->description;
  }
}

 ?>
