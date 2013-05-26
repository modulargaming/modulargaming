<?php defined('SYSPATH') OR die('No direct script access.');
 /**
 * Abstract base controller for ajax.
 *
 * @package    MG/Core
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
abstract class MG_Abstract_Controller_Ajax extends Controller {

	public function before()
	{
		parent::before();

		$this->response->headers('Content-Type', 'application/json');
	}

}
