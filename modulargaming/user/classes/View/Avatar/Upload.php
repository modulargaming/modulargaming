<?php defined('SYSPATH') OR die('No direct script access.');
 
class View_Avatar_Upload extends Abstract_View {

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
