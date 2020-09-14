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
    if(!isset($_GET['ss'])){
      if(!isset($_GET['fo'])){
        //Si il n'y a pas de GET
        if(isset($COOKIES['session'])){
          $this->setSession($COOKIES['session']);
        }else {
          $this->session = FALSE;
        }
        if(isset($COOKIES['calendrier'])){
          $this->setCalendrier($COOKIES['calendrier']);
        }else {
          $this->formation = FALSE;
        }
      }else {// Si il y a que la formation
        $this->setCalendrier(htmlspecialchars($_GET['fo']));
        if(isset($_COOKIE['session'])){
          setcookie('session', NULL, -1);
          $this->session = FALSE;
        }
      }
    }else {
      if(!isset($_GET['fo'])){ // Si il y a que la session
        $this->session = FALSE;
        $this->formation = FALSE;
      }else { // Si il y a deux GET
        $this->setSession(htmlspecialchars($_GET['ss']));
        $this->setCalendrier(htmlspecialchars($_GET['fo']));

      }
    }
    // if(isset($_GET['ss']) && !empty($_GET['ss'])){
    //   $session = intval($_GET['ss']);
    //   $this->setSession($session);
    // }else{
    //   if(isset($COOKIES['session'])){
    //     if(isset($COOKIES['calendrier'])){
    //       unset($_COOKIE['calendrier']);
    //     }
    //     $this->setSession($COOKIES['session']);
    //   }
    // }
    //
    // if(isset($_GET['fo']) && !empty($_GET['fo'])){
    //   $formation = htmlspecialchars($_GET['fo']);
    //   $this->setCalendrier($formation);
    // }else{
    //   if(isset($COOKIES['calendrier'])){
    //     $this->setCalendrier($COOKIES['calendrier']);
    //   }else{
    //     $this->setCalendrier('2020-ING-ASI-S7');
    //   }
    // }
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
