<?php namespace ClanCats\Hydrahon\Test;

class MySQLi_Result_Mock {

	private $data;

	function __construct($data = array())
	{
		$this->data = $data;
	}

	public function fetch_all() {
		return $this->data;
	}
}