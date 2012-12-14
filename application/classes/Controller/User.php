<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_User extends Controller_Frontend {

	public function action_index()
	{
		if ( ! $this->auth->logged_in())
		{
			$this->redirect('user/login');
		}
		
		die('Logged in');
	}

	/**
	 * Display the login page and handle login attempts.
	 */
	public function action_login()
	{

		if ($_POST)
		{
			$auth = Auth::instance();

			$post = $this->request->post();

			$remember = isset($post['remember']) ? (bool) $post['remember'] : FALSE;

			if ($auth->login($post['username'], $post['password'], $remember))
			{
				$this->redirect('');
			}
			else
			{
				die('error');
			}
		}

		$this->view = new View_User_Login;
	}

	public function action_register()
	{
		if ($_POST)
		{
			try
			{
				$user = ORM::Factory('User')
					->create_user($this->request->post(), array(
						'username',
						'email',
						'password'
					));

				$user->add('roles', ORM::Factory('Role')->where('name', '=', 'login')->find());
			}
			catch (ORM_Validation_Exception $e)
			{
				var_dump($e->errors('models'));
				die();
			}

			$this->redirect('');

		}

		$this->view = new View_User_Register;
	}


	/**
	 * Sign out the user and redirect him to the frontpage.
	 */
	public function action_logout()
	{
		Auth::instance()->logout();
		$this->redirect('');
	}


} // End User
