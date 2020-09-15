<?php
/**
 *
 */
class ManipulateurDate
{
  private $time;
  function __construct($time = FALSE)
  {
    if($time === FALSE) $time = time();
    $this->time = $time;
  }

  public function getDebutMois(){
    return strtotime(date('Y-m-01', $this->time));
  }

  public function getDebutSemaine(){
    return ($this->time - (intval(date("N",$this->time)) -1)*86400) - $this->time % 86400;
    //return strtotime('last Monday');
  }

  public function getTime(){
    return $this->time;
  }

  // "Lundi 07 Septembre" => "Monday 07 September"
  public static function FrenshTextToEnglish($text){
    $tab = explode(" ",$text);
    $tab[0] = ManipulateurDate::FrenshDayToEnglish($tab[0]);
    $tab[2] = ManipulateurDate::FrenshMouthToEnglish($tab[2]);
    return implode(" ",$tab);
  }

  public static function FrenshDayToEnglish($text){
    switch ($text) {
      case 'Lundi':
        return "Monday";
        break;
      case 'Mardi':
        return "Tuesday";
        break;
      case 'Mercredi':
        return "Wednesday";
        break;
      case 'Jeudi':
        return "Thursday";
        break;
      case 'Vendredi':
        return "Friday";
        break;
      case 'Samedi':
        return "Saturday";
        break;
      case 'Dimanche':
        return "Sunday";
        break;
      default:
        return FALSE;
        break;
    }
  }

  public static function FrenshMouthToEnglish($text){
    switch ($text) {
      case 'Janvier':
        return "January";
        break;
      case 'Février':
        return "February";
        break;
      case 'Mars':
        return "March";
        break;
      case 'Avril':
        return "April";
        break;
      case 'Mai':
        return "May";
        break;
      case 'Juin':
        return "June";
        break;
      case 'Juillet':
        return "July";
        break;
      case 'Août':
        return "August";
        break;
      case 'Septembre':
        return "September";
        break;
      case 'Octobre':
        return "October";
        break;
      case 'Novembre':
        return "November";
        break;
      case 'Décembre':
        return "December";
        break;
      default:
        return FALSE;
        break;
    }
  }
}
 ?>
