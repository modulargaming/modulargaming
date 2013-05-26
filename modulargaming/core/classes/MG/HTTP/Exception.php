<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/Core
 * @category   Exceptions
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_HTTP_Exception extends Kohana_HTTP_Exception {
	/**
	* Generate a Response for all Exceptions without a more specific override
	*
	* The user should see a nice error page, however, if we are in development
	* mode we should show the normal Kohana error page.
	*
	* @return Response
	*/
	/**
	 * Run CSRF check and load frontend assets.
	 */
	public function get_response()
	{
		// Lets log the Exception, Just in case it's important!
		Kohana_Exception::log($this);

		if (Kohana::$environment >= Kohana::DEVELOPMENT)
		{
			// Show the normal Kohana error page.
			return parent::get_response();
		}

		$response = Response::factory();

		$assets = Kohana::$config->load('assets.global');
		$this->_load_assets($assets);

		$view = new View_Error;
		$view->title = $this->getCode();
		$view->message = $this->getMessage();

		$renderer = Kostache_Layout::factory();
		$response->body($renderer->render($view));

		return $response;
	}
	protected function _load_assets($config)
	{
		if (isset($config['head']))
		{
			Assets::factory('head')->load($config['head']);
		}

		if (isset($config['body']))
		{
			Assets::factory('body')->load($config['body']);
		}

	}
}
