<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Display the 404 (File not found) error page.
 *
 * @package    MG/Core
 * @category   Exceptions
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_HTTP_Exception_404 extends Kohana_HTTP_Exception_404 {

	/**
	 * Display the 404 page, using internal requests to error controller.
	 *
	 * @return Response
	 */
	public function get_response()
	{
		$request = Request::factory('error/404');

		return $request->execute();
	}

}