<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Trade_Bids extends Abstract_View {

	public $title = 'Trade bids';
	
	/**
	 * Stores a bid
	 * @var array|false
	 */
	public $bids = false;
	
	/**
	 * The image URL to the defined currency image
	 * @var unknown_type
	 */
	public $currency_image = false;
	
	/**
	 * Total amount of bids the user has made
	 * @var integer
	 */
	public $count = 0;
	
	/**
	 * Stores the navigation
	 * @var array
	 */
	public $trade_nav = array();
	
	/**
	 * Return a simplified bid data definition.
	 * 
	 * @return array
	 */
	protected function _bid() {
		
		if($bid != null) {
			$items = array();
			
			foreach ($bid->items() as $item) {
				$items[] = array('name' => $item->name(), 'img' => $item->img());
			}
				
			return array (
				'id' => $bid->id,
				'points' => ($bid->points > 0) ? array('amount' => $bid->points) : false,
				'username' => $bid->lot->user->username,
				'lot' => Route::url('item.trade.lot', array('id' => $bid->lot_id)),
				'lot_id' => $bid->lot_id,
				'profile' => Route::url('user.view', array('id' => $bid->lot->user_id)),
				'inventory' => $items,
				'retract' => Route::url('item.trade.retract', array('id' => $bid->id))
			);
		}
		return false;
	}
	
	/**
	 * Simplify bid data
	 * @return array
	 */
	public function bids() {
		$list = array();

		$bids = $this->bids;
			
		if(count($bids) > 0) 
		{
			foreach($bids as $bid)
				$list[] = $this->bid($bid);
		}
		
		return $list;
	}
}
