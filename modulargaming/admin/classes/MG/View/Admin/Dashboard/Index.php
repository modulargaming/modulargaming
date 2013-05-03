<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Admin_Dashboard_Index extends Abstract_View_Admin {

	public $title = 'Admin - Dashboard - Index';

	public function feed()
	{
		$feed = $this->feed;
		return $feed;
	}


}
