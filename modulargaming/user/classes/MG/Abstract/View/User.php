<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base view for user.
 *
 * @package    MG/User
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Abstract_View_User extends Abstract_View {

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'User',
				'href'  => Route::url('user')
			)
		));
	}

} // End Abstract_View_User
