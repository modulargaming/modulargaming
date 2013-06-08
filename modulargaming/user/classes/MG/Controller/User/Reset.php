<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for recovering the username OR password.
 *
 * @package    MG/User
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_User_Reset extends Abstract_Controller_User {

	/**
	 * @var string reset token.
	 */
	private $token;

	/**
	 * Change action to 'token' if token parameter exists.
	 */
	public function before()
	{
		parent::before();

		// We got no reason to allow logged in users to access this page.
		if ($this->auth->logged_in())
		{
			$this->redirect(Route::get('user')->uri());
		}

		$this->token = $this->request->param('token');

		if ($this->token !== NULL)
		{
			$this->request->action('token');
		}
	}

	/**
	 * Display the request reset page.
	 */
	public function action_index()
	{
		if ($this->request->method() == HTTP_Request::POST)
		{
			$user = ORM::factory('User')
				->where('email', '=', $this->request->post('email'))
				->find();

			if ($user->loaded())
			{
				$user->set_property('reset_token', $this->_generate_token());
				$user->save();

				$this->_send_reset_email($user);
				Hint::success('Reset password email sent.');
			}
			else
			{
				Hint::error('No user exists with that email.');
			}
		}

		$this->view = new View_User_Reset_Request;
	}

	/**
	 * Enter new password, accessed if token is in the url.
	 */
	public function action_token()
	{
		$tokens = ORM::factory('User_Property')
			->where('key', '=', 'reset_token')
			->find_all();

		$token = $this->_get_token($tokens);

		if ( ! $token)
		{
			Hint::error('Incorrect token, perhaps it expired?');
			$this->redirect();
		}

		if ($this->request->method() == HTTP_Request::POST)
		{
			$user = $token->user;

			try
			{
				$user->update_user($this->request->post(), array('password'));

				// Delete the token.
				$token->delete();

				// Confirm and redirect the user.
				Hint::success('Password changed, please login.');
				$this->redirect(Route::get('user.login')->uri());
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}

		$this->view = new View_User_Reset_Enter;
	}

	/**
	 * Generate the reset token.
	 *
	 * @return array
	 */
	private function _generate_token()
	{
		return array(
			'token' => Text::random(),
			'time' => time()
		);
	}

	/**
	 * Send the reset email to the user.
	 *
	 * @param Model_User $user
	 */
	private function _send_reset_email(Model_User $user)
	{
		// Send the reset email.
		$view = new View_Email_User_Reset;
		$view->user = $user;
		$token = $user->get_property('reset_token');
		$view->token = $token['token'];

		Email::factory($view)
			->to($user->email)
			->send();
	}

	/**
	 * Get the token object from $tokens and delete tokens older than 2 days.
	 *
	 * @param ORM[] $tokens
	 * @return mixed
	 */
	private function _get_token($tokens)
	{
		$match = NULL;

		foreach ($tokens as $t)
		{
			// Remove tokens older than 2 days.
			if (Arr::get($t->value, 'time', 0) + Date::DAY * 2 < time())
			{
				$t->delete();
			}
			else
			{
				if (Arr::get($t->value, 'token') == $this->token)
				{
					$match = $t;
				}
			}
		}

		return $match;
	}

} // End User_Forgot
