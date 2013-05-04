<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Trade_Lot extends Abstract_View_Lot {

	public $title = 'Trade lots';

	/**
	 * Stores the trade lot data
	 * @var array
	 */
	public $lot = array();

	/**
	 * Stores a bid
	 * @var array|false
	 */
	public $bid = FALSE;

	/**
	 * Whether to show actions only an owner can perform
	 * on this lot.
	 *
	 * @var boolean
	 */
	public $owner_actions = FALSE;

	/**
	 * The image URL to the defined currency image
	 * @var unknown_type
	 */
	public $currency_image = FALSE;

	/**
	 * Stores the navigation
	 * @var array
	 */
	public $trade_nav = array();

	/**
	 * Simplify lot data and add linked item
	 * @return array
	 */
	public function lot()
	{
		$inventory = array();

		foreach ($this->lot->items() as $item)
		{
			$inventory[] = array(
				'name' => $item->name(),
				'img'  => $item->img()
			);
		}

		$lot = array(
			'id'           => $this->lot->id,
			'is_owner'     => $this->owner_actions,
			'can_bid'      => ($this->bid == FALSE && $this->owner_actions != FALSE) ? FALSE : array('link' => Route::url('item.trade.bid', array('id' => $this->lot->id))),
			'description'  => $this->lot->description,
			'inventory'    => $inventory,
			'username'     => $this->lot->user->username,
			'user_profile' => Route::url('user.profile', array('id' => $this->lot->user_id)),
			'delete_trade' => ($this->owner_actions) ? Route::url('item.trade.delete', array('id' => $this->lot->id)) : FALSE
		);

		return $lot;
	}

	/**
	 * Return a simplified bid data definition.
	 *
	 * @param User_Trade_Bid $bid
	 *
	 * @return array
	 */
	public function bid($bid = NULL)
	{
		if ($bid == NULL && $this->bid != FALSE)
		{
			$bid = $this->bid;
		}

		if ($bid != NULL)
		{
			$items = array();

			foreach ($bid->items() as $item)
			{
				$items[] = array('name' => $item->name(), 'img' => $item->img());
			}

			return array(
				'id'        => $bid->id,
				'points'    => ($bid->points > 0) ? array('amount' => $bid->points) : FALSE,
				'username'  => $bid->user->username,
				'profile'   => Route::url('user.profile', array('id' => $bid->user_id)),
				'inventory' => $items,
				'accept'    => Route::url('item.trade.accept', array('id' => $bid->id)),
				'reject'    => Route::url('item.trade.reject', array('id' => $bid->id)),
				'retract'   => Route::url('item.trade.retract', array('id' => $bid->id))
			);
		}

		return FALSE;
	}

	/**
	 * If the owner is viewing the page
	 * return bids people have made.
	 *
	 * @return array
	 */
	public function bids()
	{
		$list = array();

		if ($this->owner_actions == TRUE)
		{
			$bids = $this->lot->bids->find_all();

			if (count($bids) > 0)
			{
				foreach ($bids as $bid)
					$list[] = $this->bid($bid);
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
				'title' => 'Lot',
				'href'  => Route::url('item.trade.lot')
			)
		));
	}
}
