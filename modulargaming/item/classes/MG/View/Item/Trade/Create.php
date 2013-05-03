<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Trade_Create extends Abstract_View_Inventory {

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
				'title' => 'Create',
				'href'  => Route::url('item.trade.create')
			)
		));
	}
}
