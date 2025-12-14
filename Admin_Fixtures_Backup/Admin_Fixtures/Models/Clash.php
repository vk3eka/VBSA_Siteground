<?php

//require_once('Models/Club.php');
//require_once('Models/Grade.php');
//require_once('Models/Team.php');
//require_once('Models/Round.php');

class Clash {
	public $home;
	public $away;
	public $grade;
	public $date;
	public $round;

	public function LoadData($home, $away, $grade, $date,  $round) : void
	{
		$this->home = $home;
		$this->away = $away;
		$this->grade = $grade;
		$this->date = $date;
		$this->round = $round;
	}
}

?>