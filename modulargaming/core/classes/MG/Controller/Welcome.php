<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Welcome controller, for displaying the frontpage for not logged in visitors.
 *
 * @package    MG/Core
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Welcome extends Abstract_Controller_Frontend {

	public function action_index()
	{
		if ($this->auth->logged_in())
		{
			$this->redirect(Route::get('user.dashboard')->uri());
		}

		$this->view = new View_Welcome;
	}

} // End Welcome
