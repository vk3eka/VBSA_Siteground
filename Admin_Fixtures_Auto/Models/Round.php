<?php

class Round {

	public int $number;
	public string $date;
	public string $grade;

	public function LoadData(int $number, string $date, string $grade) : void
	{
		$this->number = $number;
		$this->date = $date;
		$this->grade = $grade;
	}
}

?>

