<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Item_Index extends Abstract_View_Admin {

	public $title = 'Items';
	public $paginate_max = 20;
	

	public function item_types()
	{
		$list = array();

		foreach ($this->item_types as $type)
		{
			$list[] = array(
				'id'         => $type->id,
				'name'   => $type->name,
				'command' => $type->default_command
			);
		}

		return $list;
	}
	
	public function items(){
		$list = array();
		
		foreach($this->items as $item) {
			$status = false;
			if($item->status != 'released')
			{
				$status = array('name' => $item->status);
			}
			
			$list[] = array(
				'id' => $item->id,
				'img' => $item->img(),
				'name' => $item->name,
				'type' => $item->type->name,
				'status' => $status
			);
		}
		
		return $list;
	}
}
