<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Shop_Index extends Abstract_View {

	public $title = 'Shop';
	
	public function shop() {
		return array_merge($this->shop, array('link' => Route::url('item.user_shop.update')));
	}
	
	public function units() {
		$extra =  array(
			'size' => $this->shop['size'], 
			'content' => $this->shop['size'] * $this->units['unit_size'],
			'link' => Route::url('item.user_shop.upgrade')
		);
		return array_merge($this->units,$extra);
	}
}
