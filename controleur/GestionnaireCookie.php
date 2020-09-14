<?php
/**
 * new GestionnaireCookie($_COOKIES)
 */
class GestionnaireCookie
{
  private $formation;
  private $session;

  public function __construct($COOKIES)
  {
    if(isset($_GET['ss']) && !empty($_GET['ss'])){
      $session = intval($_GET['ss']);
      $this->setSession($session);
    }else{
      if(isset($COOKIES['session'])){
        $this->setSession($COOKIES['session']);
      }
    }

    if(isset($_GET['fo']) && !empty($_GET['fo'])){
      $formation = htmlspecialchars($_GET['fo']);
      $this->setCalendrier($formation);
    }else{
      if(isset($COOKIES['calendrier'])){
        $this->setCalendrier($COOKIES['calendrier']);
      }else{
        $this->setCalendrier('2020-ING-ASI-S7');
      }
    }
  }

  public function setCalendrier($str){
    setcookie('calendrier', $str, time() + 365*24*3600);
    $this->formation = $str;
  }

  public function setSession($str){
    setcookie('session', $str, time() + 365*24*3600);
    $this->session = $str;
  }

  public function getCalendrier(){
    return $this->formation;
  }
  public function getSession(){
    return $this->session;
  }
}


?>
