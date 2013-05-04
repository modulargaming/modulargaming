<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for logging out the user.
 *
 * @package    MG/User
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_User_Logout extends Abstract_Controller_User {

	/**
	 * Sign out the user AND redirect him to the frontpage.
	 */
	public function action_index()
	{
		if ($this->request->method() == HTTP_Request::POST)
		{
			Hint::success(Kohana::message('user', 'logout.success'));

			$this->auth->logout();
			$this->redirect('');
		}
		else
		{
			$this->view = new View_User_Logout;
		}
	}

} // End User_Logout
