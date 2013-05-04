<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Upload custom avatar.
 *
 * @package    MG/User
 * @category   Avatar
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Avatar_Upload extends Avatar {

	public $id = 'upload';
	public $name = 'Custom (upload)';

	/**
	 * @var string prefix for avatar path.
	 */
	private $prefix = 'media/image/avatars/';

	/**
	 * Upload the image and return the image data.
	 * If no image is found on the server, swap to default avatar driver.
	 *
	 * @return array
	 */
	public function data($data)
	{
		// Upload the image
		if (isset($_FILES['avatar-upload']))
		{
			$this->save_image($_FILES['avatar-upload']);
		}

		// If user dosn't have an avatar, swap to default driver.
		if ( ! $this->file_exists())
		{
			return array(
				'driver' => 'Default'
			);
		}

		return array(
			'driver' => 'Upload'
		);
	}

	/**
	 * Return the url for the avatar.
	 *
	 * @return string
	 */
	public function url()
	{
		return URL::site($this->prefix.$this->_filename());
	}

	/**
	 * @return Abstract_View
	 */
	protected function _edit_view()
	{
		$view = new View_Avatar_Upload;

		$view->height = $this->height();
		$view->width  = $this->width();

		if ($this->file_exists())
		{
			$view->url = $this->url();
		}

		return $view;
	}

	/**
	 * Get the avatar filename.
	 *
	 * @return string filename
	 */
	private function _filename()
	{
		return $this->user->id.'.png';
	}

	/**
	 * Check if the avatar file exists.
	 *
	 * @return bool
	 */
	private function file_exists()
	{
		return file_exists(DOCROOT.$this->prefix.$this->_filename());
	}

	/**
	 * @return bool|string
	 */
	private function save_image($image)
	{
		if (
			! Upload::valid($image) OR
			! Upload::not_empty($image) OR
			! Upload::type($image, array('jpg', 'jpeg', 'png', 'gif')))
		{
			return FALSE;
		}

		$directory = DOCROOT.$this->prefix;

		if ($file = Upload::save($image, NULL, $directory))
		{
			// Save the image.
			Image::factory($file)
				->resize($this->width(), $this->height())
				->save($directory.$this->_filename());

			// Delete the temporary file
			unlink($file);

			return TRUE;
		}
	}

}
