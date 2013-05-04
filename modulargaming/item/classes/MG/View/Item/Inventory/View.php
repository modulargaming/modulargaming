<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Inventory_View extends Abstract_View_Inventory {

	public $title = 'Inventory';

	/**
	 * Store the item's information
	 * @var User_Item
	 */
	public $item = FALSE;

	/**
	 * Contains the item's action list
	 * @var array
	 */
	public $action_list = FALSE;

	/**
	 * Build the item template data, along with the action list.
	 * @return array
	 */
	public function item()
	{
		$return = array(
			'image'  => $this->item->img(),
			'amount' => $this->item->amount,
			'name'   => $this->item->item->name,
			'menu'   => array()
		);

		$url = URL::site(Route::get('item.inventory.consume')->uri(array('id' => $this->item->id)));

		foreach ($this->action_list as $type => $action)
		{
			if ($action['extra'] == NULL)
			{
				$return['menu'][] = array(
					'normal' => array(
						'url'    => $url,
						'action' => $type,
						'crsf'   => $this->csrf(),
						'text'   => $action['item']
					)
				);
			}
			else
			{
				$return['menu'][] = array(
					'extra' => array(
						'url'           => $url,
						'action'        => $type,
						'crsf'          => $this->csrf(),
						'text'          => $action['item'],
						'field_type'    => $action['extra']['field']['type'],
						'field_name'    => $action['extra']['field']['name'],
						'field_classes' => $action['extra']['field']['classes'],
						'field_button'  => $action['extra']['field']['button']
					)
				);
			}
		}

		return $return;
	}

	protected function get_breadcrumb()
	{
		$item = $this->item;

		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => $item->id,
				'href'  => Route::url('item.inventory.view', array('id' => $item->id))
			)
		));
	}
}
