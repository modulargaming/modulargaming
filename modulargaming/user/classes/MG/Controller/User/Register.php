<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for registering a new user.
 *
 * @package    MG/User
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_User_Register extends Abstract_Controller_User {

	/**
	 * Display AND handle the register form.
	 */
	public function action_index()
	{
		$this->_not_logged_in();

		if ($this->request->method() == HTTP_Request::POST)
		{
			if ($this->_honeypot_empty())
			{
				try
				{
					$user = $this->_create_user($this->request->post());

					$this->_send_welcome_email($user);

					// Log in the user, and send him to his dashboard.
					$this->auth->force_login($user);
					$this->redirect(Route::get('user')->uri());
				}
				catch (ORM_Validation_Exception $e)
				{
					Hint::error($e->errors('models'));
				}
			}
			else
			{
				Hint::error(Kohana::message('user', 'register.honeypot'));
			}
		}

		$this->view = new View_User_Register;
	}

	/**
	 * Use a fake input field (hidden) to trick bots,
	 * regular users leaves it empty while bots fill it in.
	 *
	 * @return bool honeypot field was empty
	 */
	private function _honeypot_empty()
	{
		return $this->request->post('full_name') == "";
	}

	/**
	 * Create the user.
	 *
	 * @param  array $post
	 *
	 * @return Model_User
	 */
	private function _create_user($post)
	{
		$user = ORM::factory('User')
			->create_user($post, array(
				'username',
				'email',
				'password'
			));

		// Add the login role.
		$user->add('roles', Model_Role::LOGIN);

		return $user;
	}

	/**
	 * Send the welcome email to the newly registered user.
	 *
	 * @param Model_User $user user to welcome.
	 */
	private function _send_welcome_email($user)
	{
		$view = new View_Email_User_Welcome;
		$view->user = $user;

		Email::factory($view)
			->to($user->email)
			->send();
	}

} // End User_Register
