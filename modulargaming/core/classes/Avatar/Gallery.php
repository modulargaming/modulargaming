<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Gallery avatar driver, returns a url depending on the gallery id.
 *
 * @package    Modular Gaming
 * @category   Avatar
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Avatar_Gallery extends Avatar {

	/**
	 * Return the gallery avatar.
	 *
	 * @return string
	 */
	public function url()
	{
		$id = Arr::get($this->data, 'id', 0);
		return URL::site('assets/img/avatars/gallery/'.$id.'.png', NULL, FALSE);
	}
}
