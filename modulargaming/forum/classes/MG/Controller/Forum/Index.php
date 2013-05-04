<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Forum Index.
 * Displays the forum category list.
 *
 * @package    MG Forum
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Forum_Index extends Abstract_Controller_Forum {

	/**
	 * List all forum categories.
	 */
	public function action_index()
	{
		$categories = ORM::factory('Forum_Category')
			->find_all();

		$this->view = new View_Forum_Index;
		$this->view->categories = $categories;
	}

}
