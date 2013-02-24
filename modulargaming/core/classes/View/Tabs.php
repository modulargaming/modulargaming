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
				'name'         => $tab->name,
				'machine-name' => URL::title($tab->name),
				'content'      => $tab->render(),
				'active'       => FALSE
			);
		}
		isset($tabs[0]) AND $tabs[0]['active'] = TRUE;

		return $tabs;
	}

}
