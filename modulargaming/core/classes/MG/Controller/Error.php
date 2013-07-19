<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for displaying the error pages.
 *
 * @package    MG/Core
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Error extends Abstract_Controller_Frontend {

	public function action_404()
	{
		$this->response->status(404);
		$this->view = new View_Error_404;
	}

	public function action_500()
	{
		$this->response->status(500);
		$this->view = new View_Error_500;
	}

}