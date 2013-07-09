<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Redirect 401 (not logged in) requests to the login page.
 * Add the current page as a query.
 *
 * @package    MG/Core
 * @category   Exceptions
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_HTTP_Exception_401 extends Kohana_HTTP_Exception_401 {

	/**
	 * Redirect the user to the login page.
	 * And send along the current page as a query string.
	 *
	 * @return Response
	 */
	public function get_response()
	{
		$query = '?page='.urlencode(Request::current()->uri());

		$response = Response::factory()
			->status(401)
			->headers('Location', Route::url('user.login').$query);

		return $response;
	}

}