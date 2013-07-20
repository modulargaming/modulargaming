<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Item_Shop_Logs extends Abstract_View_Inventory {

	public $title = 'Shop';

	public $logs = array();

	public function logs()
	{
		$return = array();

		if (count($this->logs) > 0)
		{
			foreach ($this->logs as $log)
			{
				$return[] = array(
					'time' => Date::fuzzy_span($log->time),
					'message' => __($log->message, array(':username' => $log->params['username'], ':item_name' => $log->params['item_name'], ':price' => $log->params['price']))
				);
			}
		}

		return $return;
	}

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Shop',
				'href'  => Route::url('item.user_shop.index')
			),
			array(
				'title' => 'Logs',
				'href'  => Route::url('item.user_shop.logs')
			)
		));
	}
}
