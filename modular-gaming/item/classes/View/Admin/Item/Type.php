<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Item_Type extends Abstract_View_Admin {

	public $title = 'Items';
	public $paginate_max = 20;

	public function types()
	{
		$list = array();

		foreach ($this->types as $type)
		{
			$list[] = array(
				'id'         => $type->id,
				'name'   => $type->name,
			);
		}

		return $list;
	}

}
