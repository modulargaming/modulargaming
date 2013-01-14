<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base controller for forum.
 *
 * @package    MG Forum
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Controller_Abstract_Forum extends Abstract_Controller_Frontend {

	protected $protected = TRUE;

	public function before()
	{
		parent::before();

		// TODO: Check if user can view forum?

		Breadcrumb::add('Forum', Route::url('forum'));
	}

}