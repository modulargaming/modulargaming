<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * View for user dashboard.
 *
 * @package    MG/User
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_User_Dashboard extends Abstract_View_User {

	public $title = 'Dashboard';

	protected function get_breadcrumb()
	{
		
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Dashboard',
				'href'  => Route::url('user.dashboard')
			)
		));
	}

}
