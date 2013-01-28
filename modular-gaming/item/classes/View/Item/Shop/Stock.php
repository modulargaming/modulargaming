<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Shop_Stock extends Abstract_View {

	public $title = 'Shop';

	public function items() {
		$list = array();
		
		if(count($this->items) > 0) {
			foreach ($this->items as $item) {
				$list[] = array(
					'id' => $item->id,
					'price' => $item->parameter,
					'img' => $item->img(),
					'name' => $item->item->name,
					'amount' => $item->amount		
				);
			}
		}
		return $list;
	}
}
