<?php

class Round {

	public string $name;
	public int $number;
	public string $date;

	public function LoadData(string $name, int $number, string $date) : void
	{
		$this->name = $name;
		$this->number = $number;
		$this->date = $date;
	}
}

?>

