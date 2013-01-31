<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Shop_Create extends Abstract_View {

	public $title = 'Shop';
	
	/**
	 * Contains 2 keys:
	 * - cost (integer)
	 * - affordable (bool) whether the user can afford it to create a shop
	 * @var array
	 */
	public $creation = false;
}
