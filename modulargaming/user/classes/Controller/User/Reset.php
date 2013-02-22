<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for recovering the username or password.
 *
 * @package    MG User
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Controller_User_Reset extends Abstract_Controller_User {

	/**
	 * Display the login page and handle login attempts.
	 */
	public function action_index()
	{
		if ($this->auth->logged_in())
		{
			$this->redirect(Route::get('user')->uri());
		}

		$token = $this->request->param('token');

		if ($token)
		{
			$tokens = ORM::factory('User_Property')
				->where('key', '=', 'reset_token')
				->find_all();

			$match = NULL;

			foreach ($tokens as $t)
			{
				// Remove tokens older than 2 days.
				if (Arr::get($t->value, 'time', 0) + Date::DAY * 2 < time())
				{
					if (Arr::get($t->value, 'token') == $token)
					{
						Hint::error('Token has expired');
					}
					$t->delete();
				}
				else
				{
					if (Arr::get($t->value, 'token') == $token)
					{
						$match = $t;
					}
				}
			}

			if ( ! $match)
			{
				Hint::error('Incorrect token');
				$this->redirect();
			}

			$this->view = new View_User_Reset_Enter;
		}
		else
		{
			if ($this->request->method() == HTTP_Request::POST)
			{
				$user = ORM::factory('User')
					->where('email', '=', $this->request->post('email'))
					->find();

				if ($user->loaded())
				{
					$token = Text::random();

					$user->set_property('reset_token', array(
						'token' => $token,
						'time'  => time()
					));
					$user->save();

					// Send the reset email.
					$view = new View_Email_User_Reset;
					$view->user = $user;
					$view->token = $token;

					Email::factory($view)
						->to($user->email)
						->send();
				}
				else
				{
					// TODO: Display error.
				}
			}

			$this->view = new View_User_Reset_Request;
		}
	}

} // End User_Forgot
