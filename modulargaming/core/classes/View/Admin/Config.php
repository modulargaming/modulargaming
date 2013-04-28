<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Config extends Abstract_View_Admin {

	public $title = 'Config';

	public function status() {
		$list = array(
			array('name' => 'open', 'title' => 'Open', 'active' => ($this->site['status'] == 'open')),
			array('name' => 'closed', 'title' => 'Closed', 'active' => ($this->site['status'] == 'closed')),
		);

		return $list;
	}
}
