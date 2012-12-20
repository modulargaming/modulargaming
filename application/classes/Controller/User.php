<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_User extends Controller_Frontend {

	public function action_index()
	{
		if ( ! $this->auth->logged_in())
		{
			$this->redirect('user/login');
		}
		
		$this->view = new View_User_Profile;
	}

	public function action_edit()
	{
		if ( ! $this->auth->logged_in())
		{
			$this->redirect('user/login');
		}

		$timezones = ORM::Factory('User_Timezone')
			->find_all();

		if ($_POST)
		{
			$post = $this->request->post();
			if ($this->auth->check_password($post['password_current']))
			{
				try
				{
					$this->user->update_user($this->request->post(), array(
						'email',
						'password'
					));

				}
				catch (ORM_Validation_Exception $e)
				{
					var_dump($e->errors('models'));
					die();
				}

				$this->auth->force_login($this->user);
				$this->redirect('user/edit');
			}
			else
			{
				die('error');
			}

		}
		
		$this->view = new View_User_Edit;
		$this->view->timezones = $timezones->as_array();
	}

	/**
	 * Display the login page and handle login attempts.
	 */
	public function action_login()
	{

		if ($this->auth->logged_in())
		{
			$this->redirect('user');
		}

		if ($_POST)
		{
			$post = $this->request->post();

			$remember = isset($post['remember']) ? (bool) $post['remember'] : FALSE;

			if ($this->auth->login($post['username'], $post['password'], $remember))
			{
				Hint::success('You have been logged in!');
				$this->redirect('');
			}
			else
			{
				Hint::error('Login information incorrect!');
			}
		}

		$this->view = new View_User_Login;
	}

	public function action_register()
	{
		if ($_POST)
		{
			// Honeypot check.
			if ($this->request->post('full_name') == "")
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

					$this->auth->force_login($user);
					$this->redirect('');
				}
				catch (ORM_Validation_Exception $e)
				{
					Hint::error($e->errors('models'));
				}
			}
			else
			{
				Hint::error('System detects you as a robot, the hidden robot check field should be empty!');
			}
		}

		$this->view = new View_User_Register;
	}

	/**
	 * Sign out the user and redirect him to the frontpage.
	 */
	public function action_logout()
	{
		Hint::success('You are now logged out!');
		$this->auth->logout();
		$this->redirect('');
	}


} // End User
