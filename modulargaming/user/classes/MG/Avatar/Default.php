 <?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Default avatar driver, returns a static url.
 *
 * @package    MG/User
 * @category   Avatar
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Avatar_Default extends Avatar {

	public $id = 'default';
	public $name = 'Default';

	/**
	 * Return the save data array.
	 *
	 * @return array
	 */
	public function data($data)
	{
		return array(
			'driver' => 'Default'
		);
	}

	/**
	 * Return the default avatar png.
	 *
	 * @return string
	 */
	public function url()
	{
		return URL::site('media/image/avatars/default.png', NULL, FALSE);
	}

	protected function _edit_view()
	{
		$view = new View_Avatar_Default;
		$view->url = $this->url();
		return $view;
	}
}
