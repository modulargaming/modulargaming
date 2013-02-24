<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_User_Edit extends Abstract_Controller_User {

	/**
	 * User preferences.
	 */
	public function action_index()
	{
		if ( ! $this->auth->logged_in())
		{
			$this->redirect(Route::get('user.login')->uri());
		}

		if ($this->request->method() == HTTP_Request::POST)
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
					$this->redirect(Route::get('user.edit')->uri());
				}
				if (array_key_exists('update_forum', $post))
				{
					$this->user->update_user($this->request->post(), array(
							'signature',
					));
					Hint::success(Kohana::message('user', 'edit.success'));
					$this->redirect(Route::get('user.edit')->uri());				}
				if (array_key_exists('remove_avatar', $post))
				{
					$avatar = 'assets/img/avatars/'.$this->user->id.'.png';
					if (file_exists($avatar))
					{
						unlink($avatar);
					}
					$this->user->update_user(
						array(
							'avatar' => '',
							'gravatar' => '0',
						),
						array(
							'avatar',
							'gravatar',
						)
					);
					Hint::success('You have removed your avatar.');
					$this->redirect(Route::get('user.edit')->uri());
				}
				if (array_key_exists('update_profile', $post))
				{
					$avatar = '';
					if (array_key_exists('gravatar', $post))
					{
						$avatar = 'http://www.gravatar.com/avatar/'.md5(strtolower($this->user->email));
						$post['gravatar'] = 1;
 					}
 					else
 					{
 						$post['gravatar'] = 0;
 						$filename = NULL;
 						if (isset($_FILES['avatar']))
						{
							$upload = $this->_save_image($_FILES['avatar'], $this->user->id);
							$avatar = URL::base().'assets/img/avatars/'.$this->user->id.'.png';
							if ( ! $upload)
							{
								$avatar = '';
								Hint::error('There was a problem while uploading your avatar. Make sure it is a JPG/PNG/GIF file.');
							}
						}
 					}
 					$post['avatar'] = $avatar;
 					$this->user->update_user($post, array(
							'about',
							'avatar',
							'gravatar',
					));
					Hint::success(Kohana::message('user', 'edit.success'));
					$this->redirect(Route::get('user.edit')->uri());
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
					$this->redirect(Route::get('user.edit')->uri());
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

	protected function _save_image($image, $user_id)
	{
		// TODO: Use validation instead?
		if ( ! Upload::valid($image) OR ! Upload::not_empty($image) OR ! Upload::type($image, array('jpg', 'jpeg', 'png', 'gif')))
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
