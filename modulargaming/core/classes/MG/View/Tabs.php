<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/Core
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_Tabs {

	/**
	 * @var Tab[]
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
