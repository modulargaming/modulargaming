<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Shop_View extends Abstract_View_Inventory {

	public $title = 'Shop';

	/**
	 * Contains shop info
	 * @var array
	 */
	public $shop = FALSE;

	/**
	 * Contains the shop's owner info
	 * @var array
	 */
	public $owner = FALSE;

	/**
	 * Contains priced User_Items
	 * @var unknown_type
	 */
	public $items = array();

	/**
	 * Parse the shop's items into inventory
	 * @return array
	 */
	public function inventory()
	{
		$list = array();

		if (count($this->items) > 0)
		{
			foreach ($this->items as $item)
			{
				$list[] = array(
					'id'    => $item->id,
					'name'  => $item->name(),
					'price' => $item->parameter,
					'img'   => $item->img(),
				);
			}
		}

		return $list;
	}

	/**
	 * simplifies the shop owner's data
	 * @return array
	 */
	public function owner()
	{
		return array('url' => Route::url('user.profile', array('id' => $this->owner['id'])), 'username' => $this->owner['username']);
	}

	public function buy()
	{
		if ($this->_user->id != $this->shop['user_id'])
		{
			return Route::url('item.user_shop.buy', array('id' => $this->shop['id']));
		}
	}

	protected function get_breadcrumb()
	{

		$shop = $this->shop;

		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Shop',
				'href'  => Route::url('item.user_shop.index')
			),
			
			array(
			'title' => $shop['title'],
			'href'  => Route::url('item.user_shop.view', array('id' => $shop['id']))
			)
			 
		));
	}
}
