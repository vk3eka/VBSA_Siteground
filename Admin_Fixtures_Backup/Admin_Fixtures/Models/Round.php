<?php

class Round {

	public int $number;
	public string $date;
	public string $grade;
	public string $type;

	public function LoadData(int $number, string $date, string $grade, string $type) : void
	{
		$this->number = $number;
		$this->date = $date;
		$this->grade = $grade;
		$this->type = $type;

	}
}

?>

