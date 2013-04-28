<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Data extends Abstract_View_Admin {

	public $title = 'Data';

	public function groups() {
		$list = array();

		foreach($this->groups as $name => $values)
		{
			$unrun = false;
			if(count($values['unrun']) > 0) {
				$unrun = $values['unrun'];
			}

			$list[] = array(
				'name' => $name,
				'last_run' => $values['last_run'],
				'has_unrun' => ($unrun != false),
				'unrun' => $unrun,
				'link' => Route::url('core.admin.modules.data.run', array('group' => $name))
			);
		}

		return $list;
	}
}
