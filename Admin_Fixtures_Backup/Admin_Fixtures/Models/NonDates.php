<?php

class NonDates {
	public string $date;
	public string $summary;

	public function LoadData(string $date, string $summary) : void
	{
		$this->date = $date;
		$this->summary = $summary;
	}
}

?>
