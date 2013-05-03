<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Trade_Lots extends Abstract_View_Inventory {

	public $title = 'Trade lots';

	/**
	 * Store the pagination HTML.
	 * @var string
	 */
	public $pagination = FALSE;

	/**
	 * Stores the trade lots
	 * @var unknown_type
	 */
	public $lots = array();

	/**
	 * Stores the navigation
	 * @var array
	 */
	public $trade_nav = array();

	/**
	 * Simplify lot data and add linked item
	 * @return array
	 */
	public function lots()
	{
		$list = array();

		if (count($this->lots) > 0)
		{
			foreach ($this->lots as $lot)
			{
				$inventory = array();

				foreach ($lot->items() as $item)
				{
					$inventory[] = array(
						'name' => $item->name(),
						'img'  => $item->img()
					);
				}

				$list[] = array(
					'id'          => $lot->id,
					'lot_link'    => Route::url('item.trade.lot', array('id' => $lot->id)),
					'description' => $lot->description,
					'inventory'   => $inventory,
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
				'title' => 'Your lots',
				'href'  => Route::url('item.trade.lots')
			),
		));
	}
}
