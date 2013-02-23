<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Shops_Index extends Abstract_View {

	public $title = 'Shops';

	/**
	 * Stores the open shops
	 * @var Model_Shop
	 */
	public $shops = array();

	/**
	 * Simplify shop data
	 * @return array
	 */
	public function shops()
	{
		$list = array();

		if (count($this->shops) > 0)
		{
			foreach ($this->shops as $shop)
			{
				$list[] = array(
					'url'  => Route::url('item.shops.view', array('id' => $shop->id)),
					'name' => $shop->title,
				);
			}
		}

		return $list;
	}
}
