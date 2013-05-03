<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Trade_Search extends Abstract_View_Inventory {

	public $title = 'Trade lots';

	/**
	 * Store the pagination HTML.
	 * @var string
	 */
	public $pagination = FALSE;

	/**
	 * Stores the found items
	 * @var Model_User_Item
	 */
	public $items = array();

	/**
	 * Holds the search term
	 * @var string
	 */
	public $term = FALSE;

	/**
	 * Stores the navigation
	 * @var array
	 */
	public $trade_nav = array();

	/**
	 * Simplify lot data and add linked items
	 * @return array
	 */
	public function lots()
	{
		$list = array();

		if (count($this->items) > 0)
		{
			foreach ($this->$items as $item)
			{
				$lot = ORM::factory('User_Trade', $item->parameter_id);

				$inventory = array();

				foreach ($lot->items() as $i)
				{
					$inventory[] = array(
						'name' => $i->name(),
						'img'  => $i->img()
					);
				}

				$list[] = array(
					'id'           => $lot->id,
					'bid_link'     => Route::url('item.trade.bid', array('id' => $lot->id)),
					'lot_link'     => Route::url('item.trade.lot', array('id' => $lot->id)),
					'description'  => $lot->description,
					'inventory'    => $inventory,
					'username'     => $lot->user->username,
					'user_profile' => Route::url('user.profile', array('id' => $lot->user_id))
				);
			}
		}

		return $list;
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Trade',
				'href'  => Route::url('item.trade.index')
			),
			array(
				'title' => ($this->term != FALSE) ? "'" . $this->term . "'" : 'Search',
				'href'  => Route::url('item.trade.search')
			)
		));
	}
}
