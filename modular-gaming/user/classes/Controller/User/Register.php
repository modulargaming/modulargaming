<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_User_Register extends Controller_User {

	
	public function action_index()
	{
		if ($this->auth->logged_in())
		{
			$this->redirect('user');
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

					$user->add('roles', ORM::factory('Role')->where('name', '=', 'login')->find());

					// Send the welcome email.
					$view = new View_Email_Welcome;
					Email::factory($view)
						->to($user->email)
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
	protected function _save_image($image, $user_id)
	{
		if (
			! Upload::valid($image) OR
			! Upload::not_empty($image) OR
			! Upload::type($image, array('jpg', 'jpeg', 'png', 'gif')))
		{
			return FALSE;
		}
		$directory = 'assets/img/avatars/';
		if ($file = Upload::save($image, NULL, $directory))
		{
			Image::factory($file)
				->resize(64, 64, Image::AUTO)
				->save($directory.$user_id.'.png');
				unlink($file);
			return TRUE;
		}
		return FALSE;
    }

}
