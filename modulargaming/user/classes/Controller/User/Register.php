<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for registering a new user.
 *
 * @package    Modular Gaming
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Controller_User_Register extends Abstract_Controller_User {

	/**
	 * Display and handle the register form.
	 */
	public function action_index()
	{
		if ($this->auth->logged_in())
		{
			$this->redirect(Route::get('user')->uri());
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			// Honeypot check.
			if ($this->request->post('full_name') == "")
			{
				try
				{
					$user = ORM::factory('User')
						->create_user($this->request->post(), array(
							'username',
							'email',
							'password'
						));

					// Add the login role.
					$user->add('roles', ORM::factory('Role')->where('name', '=', 'login')->find());

					// Send the welcome email.
					$view = new View_Email_User_Welcome;
					$view->user = $user;
					Email::factory($view)
						->to($user->email)
						->send();

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

} // End User_Register
