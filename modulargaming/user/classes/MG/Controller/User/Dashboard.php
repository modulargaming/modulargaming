<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for user dashboard, currently a WIP.
 *
 * @package    MG/User
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_User_Dashboard extends Abstract_Controller_User {

	/**
	 * Show the user dashboard.
	 */
	public function action_index()
	{
		if ( ! $this->auth->logged_in())
		{
			$this->redirect(Route::get('user.login')->uri());
		}

		$this->view = new View_User_Dashboard;
	}

}
