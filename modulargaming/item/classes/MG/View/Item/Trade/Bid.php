<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Trade_Bid extends Abstract_View_Inventory {

	public $title = 'Trade lots';

	/**
	 * transferable items that are located in the player's inventory
	 * @var array
	 */
	public $items = array();

	/**
	 * Maximum amount of items a user can trade
	 * @var integer
	 */
	public $max_items = 0;

	/**
	 * Contains trade lot data
	 * @var Model_User_Trade
	 */
	public $lot = FALSE;

	/**
	 * Simplify lot data
	 */
	public function lot()
	{
		if ($this->lot != FALSE && $this->lot->loaded())
		{
			$items = array();

			foreach ($this->lot->items() as $item)
			{
				$items[] = array(
					'name' => $item->name(),
					'img'  => $item->img(),
				);
			}

			return array(
				'id'          => $this->lot->id,
				'url'         => Route::url('item.trade.lot', array('id' => $this->lot->id)),
				'username'    => $this->lot->user->username,
				'profile'     => Route::url('user.profile', array('id' => $this->lot->user->id)),
				'inventory'   => $items,
				'description' => $this->lot->description
			);
		}

		return FALSE;
	}

	/**
	 * Simplify item data
	 * @return array
	 */
	public function items()
	{
		$list = array();

		if (count($this->items) > 0)
		{
			foreach ($this->items as $item)
			{
				$list[] = array(
					'id'   => $item->id,
					'name' => $item->name(),
					'img'  => $item->img(),
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
				'title' => 'Bid',
				'href'  => Route::url('item.trade.bid')
			)
		));
	}
}
