<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Inventory_Index extends Abstract_View {

	public $title = 'Inventory';
	
	public function items(){
		$list = array();
		
		foreach($this->items as $item) {			
			$list[] = array(
				'action_link' => URL::site(Route::get('item.inventory.view')->uri(array('id' => $item->id))),
				'img' => $item->item->img(),
				'name' => $item->name(),
				'id' => $item->id
			);
		}
		
		return $list;
	}
}
