<?php defined('SYSPATH') OR die('No direct script access.');

class HTTP_Exception_403 extends Kohana_HTTP_Exception_403 {

	/**
	* Generate a Response for all Exceptions without a more specific override
	*
	* The user should see a nice error page, however, if we are in development
	* mode we should show the normal Kohana error page.
	*
	* @return Response
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
		else
		{
			$response = Response::factory();
			$view = new View_Error_403;
			$renderer = Kostache_Layout::factory();
			$response->body ($renderer->render($view));
			return $response;
   		}
    	}
}
