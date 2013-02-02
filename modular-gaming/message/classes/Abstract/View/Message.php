<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base view for Messages.
 *
 * @package    MG Message
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Abstract_View_Message extends Abstract_View {

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Messages',
				'href'  => Route::url('messages')
			)
		));
	}

} // End Abstract_View_Message