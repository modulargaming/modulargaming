<?php defined('SYSPATH') OR die('No direct script access.');

class DataTable {

	protected $_columns = array();
	protected $_model = NULL;
	protected $_request = NULL;

	public function __construct(ORM $model, Request $request)
	{
		$this->_model = $model;
	}

	public function add_columns(Array $columns)
	{
		foreach ($columns as $column) {
			$this->add_column($column);
		}
	}

	public function add_column($name)
	{
		$this->columns[] = $name;
	}

	public function process($max_results)
	{
		$paginate = Paginate::factory($items, array ('total_items' => $max_items), $this->_request)->execute();
	}
}
