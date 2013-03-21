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
class Abstract_Controller_Forum extends Abstract_Controller_Frontend {

//	protected $protected = TRUE;
	protected $protected = FALSE;

	public function before()
	{
		parent::before();

		// TODO: Check if user can view forum?
	}

}
