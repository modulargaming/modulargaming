<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Shops_Index extends Abstract_View {

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

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Shops',
				'href'  => Route::url('item.shops.index')
			)
		));
	}
}
