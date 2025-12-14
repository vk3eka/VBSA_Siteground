<?php

class Club {
	public string $name;
	public int $id;
	public int $tables;

	public function __construct()
	{}

	public function LoadData(string $name, int $id, int $tables) : void
	{
		$this->name = $name;
		$this->id = $id;
		$this->tables = $tables;
	}
}

?>