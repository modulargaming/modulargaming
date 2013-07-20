<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Shop_Index extends Abstract_View_Inventory {

	public $title = 'Shop';

	/**
	 * Contains user shop model
	 * @var User_Shop
	 */
	public $shop = FALSE;

	/**
	 * Contains a unit's size
	 * @var integer|false
	 */
	public $units = FALSE;

	/**
	 * formats user shop data
	 */
	public function shop()
	{
		return array_merge($this->shop, array('link' => Route::url('item.user_shop.update')));
	}

	public function collect()
	{
		return Route::url('item.user_shop.collect');
	}

	/**
	 * Calculates user shop unit size, inventory size
	 */
	public function units()
	{
		$extra = array(
			'size'    => $this->shop['size'],
			'content' => $this->shop['size'] * $this->units['unit_size'],
			'link'    => Route::url('item.user_shop.upgrade')
		);

		return array_merge($this->units, $extra);
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Shop',
				'href'  => Route::url('item.user_shop.index')
			)
		));
	}
}
