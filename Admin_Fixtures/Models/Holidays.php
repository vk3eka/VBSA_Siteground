<?php

class Holidays {
	
	public string $date;

	public function LoadData(string $date) : void
	{
		$this->date = $date;
	}
}

?>