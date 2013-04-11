<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for logging in the user.
 *
 * @package    MG/User
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Controller_User_Login extends Abstract_Controller_User {

	/**
	 * Display the login page AND handle login attempts.
	 */
	public function action_index()
	{
		$this->_not_logged_in();

		if ($this->request->method() == HTTP_Request::POST)
		{
			$post = $this->request->post();

			if ($this->auth->login($post['username'], $post['password'], isset($post['remember'])))
			{
				Hint::success(Kohana::message('user', 'login.success'));
				$this->redirect(Route::get('user.dashboard')->uri());
			}
			else
			{
				Hint::error(Kohana::message('user', 'login.incorrect'));
			}
		}

		$this->view = new View_User_Login;
	}

} // End User_Login
