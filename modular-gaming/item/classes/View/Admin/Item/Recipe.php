<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Item_Recipe extends Abstract_View_Admin {

	public $title = 'Recipes';
	
	/**
	 * How many recipes can be shown per page?
	 * @var integer
	 */
	public $paginate_max = 20;
	
	/**
	 * Contains an array with Item_Recipe models
	 * @var array
	 */
	public $recipes = array();
	
	
	/**
	 * Simplify recipe data
	 * @return array
	 */
	public function recipes()
	{
		$list = array();

		if(count($this->recipes) > 0)
		{
			foreach ($this->recipes as $type)
			{
				$list[] = array (
					'id'        	=> $type->id,
					'name'   		=> $type->name,
					'crafted'		=> $type->item->img(),
					'ingredients'	=> $type->materials->count_all()
				);
			}
		}

		return $list;
	}

}
