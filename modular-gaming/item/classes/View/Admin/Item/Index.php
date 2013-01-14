<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Item_Index extends Abstract_View_Admin {

	public $title = 'Items';

	public function item_types()
	{
		$list = array();

		foreach ($this->item_types as $type)
		{
			$list[] = array(
				'id'         => $type->id,
				'name'   => $type->name,
			);
		}

		return $list;
	}
	
	public function items(){
		$list = array();
		
		foreach($this->items as $item) {
			$list[] = array(
				'id' => $item->id,
				'img' => $item->img(),
				'name' => $item->name,
				'type' => $item->type->name
			);
		}
		
		return $list;
	}

}
