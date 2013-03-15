<?php defined('SYSPATH') OR die('No direct script access.');
 
class ORM extends Kohana_ORM {

	public function list_columns()
	{
		$name = 'table_columns_'.$this->_object_name;

		if (Cache::instance()->get($name) AND Kohana::$environment === Kohana::PRODUCTION)
		{
			return Cache::instance()->get($name);
		}

		$columns = $this->_db->list_columns($this->_table_name);

		Cache::instance()->set($name, $columns);

		// Proxy to database
		return $columns;
	}

}
