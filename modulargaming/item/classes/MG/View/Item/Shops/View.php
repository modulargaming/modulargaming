<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Shops_View extends Abstract_View {

	public $title = 'Shops';

	/**
	 * Store the shop's id for routing
	 * @var integer
	 */
	public $shop_id = FALSE;

	/**
	 * Store the shop's title
	 * @var string
	 */
	public $shop_title = FALSE;

	/**
	 * Contains the NPC's image
	 * @var string
	 */
	public $npc_img = FALSE;

	/**
	 * Contains the NPC's text
	 * @var string
	 */
	public $npc_text = FALSE;

	/**
	 * Contains the shop's current stock count
	 * @var integer
	 */
	public $stock_count = 0;

	/**
	 * Contains the shop's inventory
	 * @var Model_Shop_Inventory
	 */
	public $items = FALSE;

	/**
	 * Build the item template data.
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
					'name'  => $item->item->name($item->stock),
					'img'   => $item->item->img(),
					'id'    => $item->item_id,
					'price' => $item->price,
					'url'   => Route::url('item.shops.buy', array('id' => $this->shop_id))
				);
			}
		}

		return $list;
	}

	protected function get_breadcrumb()
	{

		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => $this->title,
				'href'  => Route::url('item.shops.index')
			),
			array(
				'title' => $this->shop_title,
				'href'  => Route::url('item.shops.view', array('id' => $this->shop_id))
			)
		));
	}
}
