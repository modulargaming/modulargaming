<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Item_Recipe extends Abstract_View_Admin {

	public $title = 'Recipes';
	public $paginate_max = 20;

	public function recipes()
	{
		$list = array();

		foreach ($this->recipes as $type)
		{
			$list[] = array(
				'id'        	=> $type->id,
				'name'   		=> $type->name,
				'crafted'		=> $type->item->img(),
				'ingredients'	=> $type->materials->count_all()
			);
		}

		return $list;
	}

}
