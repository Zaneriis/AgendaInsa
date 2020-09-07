<?php
	/**
	 * Class qui permet de contruire l'agenda
	 */

	 require('simple_html_dom.php');
	 require('BDD.php');

	class AgendaFactory
	{
		private $AllDay;
		private $currentDay;
		private $currentDayText;
		private $session_id = FALSE;

		private $filtre_cache;

		function __construct($session_id_var = FALSE)
		{
			if($session_id_var !== FALSE) $this->session_id = $session_id_var;
			$this->AllDay = array();
			$this->currentDay = array();
			$this->currentDayText = "";
		}

		private function pushDay($textNextDay = FALSE){
			if(!empty($this->currentDay)){
				$this->AllDay[$this->currentDayText] = $this->currentDay;
			}
			if($textNextDay !== FALSE) $this->createNewDay($textNextDay);
		}

		private function createNewDay($str){
			$this->currentDayText = $str;
			$this->currentDay = array();
		}

		private function addToCurrentDay($time, $comment){
			$rdv = array('time' => strip_tags($time), 'comment' =>strip_tags($comment));
			array_push($this->currentDay, $rdv);
		}

		private function getAgenda(){
			return $this->AllDay;
		}

		private function resetCache(){
			$this->filtre_cache = FALSE;
		}

		private function loadCache(){
			if($this->session_id === FALSE) return;
			$bdd = BDD::load();
			$cur = $bdd->prep("SELECT * from filtres where id_session = :id and Actif = 1;");
			//$bdd->addParam($cur,"id",$this->session_id);
			$this->filtre_cache = $bdd->lirePrep($cur, array("id"=>$this->session_id));
		}

		private function checkFiltre($str){
			foreach ($this->filtre_cache as $value) {
				if(!$this->checkOneFiltre($str,$value['pattern'])) return false;
			}
			return true;
		}
		private function checkOneFiltre($str,$pattern)
		{
			return strpos($str,$pattern) === FALSE;
		}

		public function createAgenda($url, $session_id = FALSE){
			if($session_id !== FALSE) $this->session_id = $session_id;
			$html = file_get_html($url);

			if($this->session_id !== FALSE){
				$this->loadCache();
			}

			foreach ($html->find('body center table',0)->children(2)->find('div') as $value) {
				if(isset($value->class) && $value->class == 'V12')
				{
					$this->pushDay($value->plaintext);
				}else{
					$rdv = $value->find('tr');
					$time = $rdv[0]->find('td',1);
					$comment = $rdv[1]->find('td',1);
					if($this->session_id === FALSE || $this->checkFiltre($comment))
						$this->addToCurrentDay($time,$comment);
				}
			}
			$this->pushDay($value->plaintext);

			$this->resetCache();
			return $this->getAgenda();
		}



	}
?>
