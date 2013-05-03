<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base view for forum admin.
 *
 * @package    MG Forum
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Abstract_View_Admin_Forum extends Abstract_View_Admin {

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Forum',
				'href'  => Route::url('forum.admin')
			)
		));
	}

}
