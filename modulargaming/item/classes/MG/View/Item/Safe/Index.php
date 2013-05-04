<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Safe_Index extends Abstract_View_Inventory {

	public $title = 'Safe';
	/**
	 * Pagination HTML
	 * @var string
	 */
	public $pagination = FALSE;

	/**
	 * form submit url
	 * @var string
	 */
	public $process_url = FALSE;

	/**
	 * Whether or not the user has a shop
	 * @var boolean
	 */
	public $shop = FALSE;

	/**
	 * Contains User_Items
	 * @var array
	 */
	public $items = NULL;

	/**
	 * Format item nav
	 * @var aray
	 */
	public $links = array();

	/**
	 * Format item data
	 * @return array
	 */
	public function items()
	{
		$list = array();

		$options = array();
		$options[] = array('name' => 'Inventory', 'value' => 'inventory');

		if ($this->shop == TRUE)
		{
			$options[] = array('name' => 'Shop', 'value' => 'shop');
		}

		if (count($this->items) > 0)
		{
			foreach ($this->items as $item)
			{
				$list[] = array(
					'img'     => $item->item->img(),
					'name'    => $item->item->name,
					'amount'  => $item->amount,
					'id'      => $item->id,
					'options' => $options
				);
			}
		}

		return $list;
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Safe',
				'href'  => Route::url('item.safe')
			)
		));
	}
}
