<?php

class Grade {
	public string $name;
	public int $id;

	public function LoadData(string $name, int $id) : void
	{
		$this->name = $name;
		$this->id = $id;
	}
}

?>