<?php

require_once('Models/Club.php');
require_once('Models/Grade.php');

class Team {
	public string $name;
	public int $id;
	public Grade $grade;
	public Club $club;

	public function LoadData(string $name, int $id, Grade $grade, Club $club) : void
	{
		$this->name = $name;
		$this->id = $id;
		$this->grade = $grade;
		$this->club = $club;
	}
}

?>