<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Inventory_Index extends Abstract_View_Inventory {

	public $title = 'Inventory';

	/**
	 * Store the pagination HTML.
	 * @var string
	 */
	public $pagination = FALSE;

	/**
	 * Stores the user's inventory items
	 * @var unknown_type
	 */
	public $item = array();

	/**
	 * Build the item nav
	 * @var array
	 */
	public $links = array();

	/**
	 * Whether or not to include the csrf span element
	 * @return boolean
	 */
	public $ajax = FALSE;

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
					'action_link' => URL::site(Route::get('item.inventory.view')->uri(array('id' => $item->id))),
					'img'         => $item->item->img(),
					'name'        => $item->name(),
					'id'          => $item->id
				);
			}
		}

		return $list;
	}
}
