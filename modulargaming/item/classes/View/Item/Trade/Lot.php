<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Trade_Lot extends Abstract_View_Lot {

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
	public $bid = false;

	/**
	 * Whether to show actions only an owner can perform
	 * on this lot.
	 *
	 * @var boolean
	 */
	public $owner_actions = false;

	/**
	 * The image URL to the defined currency image
	 * @var unknown_type
	 */
	public $currency_image = false;

	/**
	 * Stores the navigation
	 * @var array
	 */
	public $trade_nav = array();

	/**
	 * Simplify lot data and add linked item
	 * @return array
	 */
	public function lot(){
		$inventory = array();

		foreach ($this->lot->items() as $item) {
			$inventory[] = array(
				'name' => $item->name(),
				'img' => $item->img()
			);
		}

		$lot = array (
			'id' => $this->lot->id,
			'is_owner' => $this->owner_actions,
			'can_bid' => ($this->bid == false && $this->owner_actions != false) ? false: array('link' => Route::url('item.trade.bid', array('id' => $this->lot->id))),
			'description' => $this->lot->description,
			'inventory' => $inventory,
			'username' => $this->lot->user->username,
			'user_profile' => Route::url('user.view', array('id' => $this->lot->user_id)),
			'delete_trade' => ($this->owner_actions) ? Route::url('item.trade.delete', array('id' => $this->lot->id)) : false
		);

		return $lot;
	}

	/**
	 * Return a simplified bid data definition.
	 *
	 * @param User_Trade_Bid $bid
	 * @return array
	 */
	public function bid($bid=null) {
		if($bid == null && $this->bid != false)
		{
			$bid = $this->bid;
		}

		if ($bid != null) {
			$items = array();

			foreach ($bid->items() as $item) {
				$items[] = array('name' => $item->name(), 'img' => $item->img());
			}

			return array (
				'id' => $bid->id,
				'points' => ($bid->points > 0) ? array('amount' => $bid->points) : false,
				'username' => $bid->user->username,
				'profile' => Route::url('user.view', array('id' => $bid->user_id)),
				'inventory' => $items,
				'accept' => Route::url('item.trade.accept', array('id' => $bid->id)),
				'reject' => Route::url('item.trade.reject', array('id' => $bid->id)),
				'retract' => Route::url('item.trade.retract', array('id' => $bid->id))
			);
		}
		return false;
	}

	/**
	 * If the owner is viewing the page
	 * return bids people have made.
	 *
	 * @return array
	 */
	public function bids() {
		$list = array();

		if($this->owner_actions == true)
		{
			$bids = $this->lot->bids->find_all();

			if(count($bids) > 0)
			{
				foreach($bids as $bid)
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
