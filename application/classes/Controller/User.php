<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_User extends Controller_Frontend {

	public function action_index()
	{
		if ( ! $this->auth->logged_in())
		{
			$this->redirect('user/login');
		}
		
		die('Logged in');
	}

	public function action_login()
	{

		if ($_POST)
		{
			$auth = Auth::instance();

			$post = $this->request->post();

			$remember = isset($post['remember']) ? (bool) $post['remember'] : FALSE;

			if ($auth->login($post['username'],$post['password'], $remember))
			{
				die('logged in');
				//$this->redirect('dashboard');
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


	public function action_logout()
	{
		// Sign out the user
		Auth::instance()->logout();
		$this->redirect('');
	
	}



} // End User
