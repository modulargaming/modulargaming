<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Shop_View extends Abstract_View {

	public $title = 'Shop';
	
	public function inventory() {
		$list = array();
		
		if(count($this->items) > 0) {
			foreach($this->items as $item) {
				$list[] = array(
					'id' => $item->id,
					'name' => $item->name(),
					'price' => $item->parameter,
					'img' => $item->img()	
				);
			}
		}
		
		return $list;
	}
		
	public function owner() {
		return array('url' => Route::url('user.view', array('id' => $this->owner['id'])), 'username' => $this->owner['username']);
	}
}
