<?php

require_once('Models/Club.php');
require_once('Models/Grade.php');

class Team {
	public string $name;
	public int $id;
	public string $grade;
	public string $club;
	//public Grade $grade;
	//public Club $club;

	public function LoadData(string $name, int $id, string $grade, string $club) : void
	{
		$this->name = $name;
		$this->id = $id;
		$this->grade = $grade;
		$this->club = $club;
	}
}

?>