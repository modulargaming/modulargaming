<?php defined('SYSPATH') OR die('No direct script access.');

class View_Tabs {

	/**
	 * @var array Tabs
	 */
	public $tabs;

	public function tabs()
	{
		$tabs = array();
		foreach ($this->tabs as $tab)
		{
			$tabs[] = array(
				'name'    => $tab->name,
				'content' => $tab->render(),
				'active'  => false
			);
		}
		isset($tabs[0]) && $tabs[0]['active'] = true;

		return $tabs;
	}

}
