<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Cookbook_Index extends Abstract_View {

	public $title = 'Cook book';
	
	public function recipes(){
		$list = array();
		
		foreach($this->recipes as $item) {			
			$list[] = array(
				'url' => URL::site(Route::get('item.cookbook.view')->uri(array('id' => $item->id))),
				'img' => $item->item->img(),
				'name' => $item->item->name,
				'id' => $item->id
			);
		}
		
		return $list;
	}
}
