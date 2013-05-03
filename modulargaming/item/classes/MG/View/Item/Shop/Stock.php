<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Shop_Stock extends Abstract_View_Inventory {

	public $title = 'Shop';

	/**
	 * Contains a list of User_item
	 * @var array
	 */
	public $items = array();

	/**
	 * Pagination HTML
	 * @var string
	 */
	public $pagination = FALSE;

	/**
	 * Where to submit the form to
	 * @var string
	 */
	public $inventory_url = FALSE;

	/**
	 * Simplify User_item data for the template.
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
					'id'     => $item->id,
					'price'  => $item->parameter,
					'img'    => $item->img(),
					'name'   => $item->item->name,
					'amount' => $item->amount
				);
			}
		}

		return $list;
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Shop',
				'href'  => Route::url('item.user_shop.index')
			),
			array(
				'title' => 'Stock',
				'href'  => Route::url('item.user_shop.stock')
			)
		));
	}
}
