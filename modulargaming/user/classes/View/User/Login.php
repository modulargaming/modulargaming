<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * View for user login page.
 *
 * @package    MG/User
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class View_User_Login extends Abstract_View_User {

	public $title = 'Login';

	public function links()
	{
		return array(
			'forgot' => Route::url('user.reset')
		);
	}

	protected function get_breadcrumb()
	{
		
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Login',
				'href'  => Route::url('user.login')
			)
		));
	}

}
