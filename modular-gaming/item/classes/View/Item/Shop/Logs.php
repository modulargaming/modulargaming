<?php defined('SYSPATH') OR die('No direct script access.');

class View_Item_Shop_Logs extends Abstract_View {

	public $title = 'Shop';
	
	public $logs = array();
	
	public function logs() {
		$return = array();
		
		if(count($this->logs) > 0)
		{
			foreach($this->logs as $log) 
			{
				$return[] = array('time' => Date::fuzzy_span($log->time), 'img' => $log->param['item']->img(), 'name' => $log->param['item_name'], 'price' => $log->param['amount']);
			}
		}
		
		return $return;
	}
}
