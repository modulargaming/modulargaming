<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Gallery avatar driver, returns a url depending on the gallery id.
 *
 * @package    MG/User
 * @category   Avatar
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Avatar_Gallery extends Avatar {

	public $id = 'gallery';
	public $name = 'Gallery';

	/**
	 * Return the save data array.
	 *
	 * @return array
	 */
	public function data($data)
	{
		return array(
			'driver' => 'Gallery',
			'id'     => $data['avatar-gallery']['id']
		);
	}

	/**
	 * Check to ensure the user "owns" the gallery
	 *
	 * @param Validation $validation
	 */
	public function validate($validation)
	{
		$validation->rule(
			'avatar-gallery[id]',
			array($this, 'user_has_avatar'),
			array(':validaiton', ':field', ':value')
		);
	}

	/**
	 * @param Validation $validation
	 * @param string $field
	 * @param string $value
	 */
	public function user_has_avatar($validation, $field, $value)
	{
		if ( ! $this->user->has('avatars', $value))
		{
			$validation->error($field, 'You do not own that avatar');
		}
	}

	/**
	 * Return the gallery avatar.
	 *
	 * @return string
	 */
	public function url()
	{
		$id = Arr::get($this->data, 'id', 0);
		return URL::site('media/image/avatars/gallery/'.$id.'.png', NULL, FALSE);
	}

	protected function _edit_view()
	{
		$view = new View_Avatar_Gallery;
		$view->avatars = $this->user->avatars->find_all();
		return $view;
	}

}
