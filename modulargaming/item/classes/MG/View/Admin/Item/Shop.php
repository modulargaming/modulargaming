<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Admin_Item_Shop extends Abstract_View_Admin {

	public $title = 'Shop';

	/**
	 * Command definition for javascript interface, so it knows how to deal with the commands
	 * @var array
	 */
	public $image = array('width' => 0, 'height' => 0);
}
