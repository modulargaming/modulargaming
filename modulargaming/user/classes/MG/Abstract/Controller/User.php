<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract controller for user.
 *
 * @package    MG/User
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Abstract_Controller_User extends Abstract_Controller_Frontend {

	/**
	 * Redirect the user to dashboard if he is already logged in.
	 */
	protected function _not_logged_in()
	{
		if ($this->auth->logged_in())
		{
			$this->redirect(Route::get('user')->uri());
		}
	}

} // End User
