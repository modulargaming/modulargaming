<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Gravatar avatar driver.
 * Gravatar is a centralized avatar system.
 *
 * @package    MG/User
 * @category   Avatar
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Avatar_Gravatar extends Avatar {

	public $id = 'gravatar';
	public $name = 'Gravatar';

	private $url = 'http://www.gravatar.com/avatar/';

	/**
	 * Return the save data array.
	 *
	 * @return array
	 */
	public function data($data)
	{
		return array(
			'driver' => 'Gravatar'
		);
	}

	/**
	 * Return the url for the avatar.
	 *
	 * @return string
	 */
	public function url()
	{
		return $this->url.md5(strtolower($this->user->email)).'?s=64';
	}

	protected function _edit_view()
	{
		$view = new View_Avatar_Gravatar;
		$view->url = $this->url();
		return $view;
	}

}
