<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Shop_View extends Abstract_View {

	public $title = 'Shop';
	
	/**
	 * Contains shop info
	 * @var array
	 */
	public $shop = false;
	
	/**
	 * Contains the shop's owner info
	 * @var array
	 */
	public $owner = false;
	
	/**
	 * Contains priced User_Items
	 * @var unknown_type
	 */
	public $items = array();
	
	/**
	 * Parse the shop's items into inventory
	 * @return array
	 */
	public function inventory() {
		$list = array();
		
		if(count($this->items) > 0) 
		{
			foreach($this->items as $item) {
				$list[] = array (
					'id' => $item->id,
					'name' => $item->name(),
					'price' => $item->parameter,
					'img' => $item->img()	
				);
			}
		}
		
		return $list;
	}

	/**
	 * simplifies the shop owner's data
	 * @return array
	 */
	public function owner() {
		return array('url' => Route::url('user.view', array('id' => $this->owner['id'])), 'username' => $this->owner['username']);
	}
}
