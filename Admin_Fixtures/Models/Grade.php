<?php

class Grade {
	public string $name;
	public int $id;
	public int $count;

	public function LoadData(string $name, int $id, int $count) : void
	{
		$this->name = $name;
		$this->id = $id;
		$this->count = $count;
	}
}

?>