<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_User extends Controller_Frontend {

	/**
	 * Show the user dashboard.
	 */
	public function action_index()
	{
		if ( ! $this->auth->logged_in())
		{
			$this->redirect('user/login');
		}
		
		$this->view = new View_User_Dashboard;
	}

	/**
	 * User preferences.
	 */
	public function action_edit()
	{
		if ( ! $this->auth->logged_in())
		{
			$this->redirect('user/login');
		}

		if ($_POST)
		{
			$post = $this->request->post();
			try
			{
				if (array_key_exists('update_preferences', $post))
				{
					$this->user->update_user($this->request->post(), array(
							'email',
							'timezone_id',
					));
					Hint::success(Kohana::message('user', 'edit.success'));
					$this->redirect('user/edit');
				}
				if (array_key_exists('update_forum', $post))
				{
					$this->user->update_user($this->request->post(), array(
							'signature',
					));
					Hint::success(Kohana::message('user', 'edit.success'));
					$this->redirect('user/edit');
				}
				if (array_key_exists('update_profile', $post))
				{
					$this->user->update_user($this->request->post(), array(
							'about',
					));
					Hint::success(Kohana::message('user', 'edit.success'));
					$this->redirect('user/edit');
				}
				if (array_key_exists('update_password', $post))
				{
					if ($this->auth->check_password($post['password_current']))
					{
						$this->user->update_user($this->request->post(), array(
							'password',
						));
						Hint::success(Kohana::message('user', 'edit.success'));
					}
					else
					{
						Hint::error('Incorrect password');
					}
					$this->redirect('user/edit');
				}
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}
		}

		$timezones = ORM::factory('User_Timezone')
			->find_all();

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

			if ($this->auth->login($post['username'], $post['password'], isset($post['remember'])))
			{
				Hint::success(Kohana::message('user', 'login.success'));
				$this->redirect('');
			}
			else
			{
				Hint::error(Kohana::message('user', 'login.incorrect'));
			}
		}

		$this->view = new View_User_Login;
	}

	public function action_register()
	{
		if ($this->auth->logged_in())
		{
			$this->redirect('user');
		}

		if ($_POST)
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

					$user->add('roles', ORM::factory('Role')->where('name', '=', 'login')->find());

					$email = Email::factory('New Registration', 'Thank you for registering on our game.')
						->to($user->email)
						->from('support@modulargaming.com', 'Modular Gaming')
						->send();

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
				Hint::error(Kohana::message('user', 'register.honeypot'));
			}
		}

		$this->view = new View_User_Register;
	}

	/**
	 * Sign out the user and redirect him to the frontpage.
	 */
	public function action_logout()
	{
		if ($_POST)
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

	/**
	 * View users profile
	 */
	public function action_view()
	{
		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::Factory('404', 'No such user');
		}

		$this->view = new View_User_Profile;
		$this->view->profile_user = $user->as_array();
	}


} // End User
