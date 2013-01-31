<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Safe_Index extends Abstract_View {

	public $title = 'Safe';
	
	public function items(){
		$list = array();
		
		$options = array();
		$options[] = array('name' => 'Inventory', 'value' => 'inventory');
		if($this->shop == true)
		{
			$options[] = array('name' => 'Shop', 'value' => 'shop');
		}
		
		foreach($this->items as $item) {			
			$list[] = array(
				'img' => $item->item->img(),
				'name' => $item->item->name,
				'amount' => $item->amount,
				'id' => $item->id,
				'options' => $options
			);
		}
		
		return $list;
	}
}
