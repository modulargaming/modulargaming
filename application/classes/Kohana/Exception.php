<?php defined('SYSPATH') OR die('No direct access');
/**
 * Kohana Exception handler, attempts to use the error/500 controller,
 * and if it fails revert back to basic error message.
 *
 * @package    MG/Core
 * @category   Exceptions
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Kohana_Exception extends Kohana_Kohana_Exception {

	/**
	 * @var string
	 */
	private static $_controller = 'error';

	/**
	 * @var string
	 */
	private static $_method = '500';

	/**
	 * Attempt to query error/500 manually, as we cannot catch exceptions through traditional HMVC requests.
	 * Catch potential exceptions such as database is down and display a lightweight error message.
	 *
	 * @param Exception $e
	 * @return Response
	 */
	public static function response(Exception $e)
	{
		if (Kohana::$environment >= Kohana::DEVELOPMENT)
		{
			return parent::response($e);
		}

		try {
			// Create the request, we need to set controller and action manual as they
			// otherwise gets set in execute method.
			$request = Request::factory();
			$request->controller(Kohana_Exception::$_controller);
			$request->action(Kohana_Exception::$_method);

			// Setup the response object
			$response = Response::factory();
			$response->status(500);
			$response->headers('Content-Type', Kohana_Exception::$error_view_content_type.'; charset='.Kohana::$charset);

			// Call the controller.
			$controller = new Controller_Error($request, $response);
			return $controller->execute();
		}
		catch (Exception $e)
		{
			// Render the fallback view.
			$view = View::factory('errors/fallback');

			$response = Response::factory();
			$response->status(500);
			$response->body($view->render());

			return $response;
		}
	}
}
