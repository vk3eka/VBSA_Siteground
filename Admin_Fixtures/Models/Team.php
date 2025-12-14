<?php

require_once('Models/Club.php');
require_once('Models/Grade.php');

class Team {
	public string $name;
	public int $id;
	public string $grade;
	public string $club;
	public string $type;
	public string $dayplayed;
	public int $max_count;

	public function LoadData(string $name, int $id, string $grade, string $club, string $type, string $dayplayed, int $max_count) : void
	{
		$this->name = $name;
		$this->id = $id;
		$this->grade = $grade;
		$this->club = $club;
		$this->type = $type;
		$this->dayplayed = $dayplayed;
		$this->max_count = $max_count;
	}
}

?>