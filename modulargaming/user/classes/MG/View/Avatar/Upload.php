<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/User
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_Avatar_Upload extends Abstract_View {

	/**
	 * @var String url to avatar
	 */
	public $url = NULL;

	/**
	 * @var int height
	 */
	public $height;

	/**
	 * @var int width
	 */
	public $width;

	/**
	 * @return string current avatar or default.
	 */
	public function url()
	{
		return $this->url ? $this->url : 'http://www.placehold.it/'.$this->width.'x'.$this->height.'/EFEFEF/AAAAAA';
	}

}
