<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for serving assets (images, css, js) to visitors.
 * Saves the files inside assets so they can be served directly by the webserver.
 *
 * @package    Modular Gaming
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Controller_Assets extends Controller {

	/**
	 * Serve the file to the browser and save it for direct access if in STAGING or PRODUCTION.
	 */
	public function action_index()
	{
		$file = $this->request->param('file');
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		$path = Kohana::find_file('assets', $file, FALSE);

		if ($path === FALSE)
		{
			throw HTTP_Exception::factory('404', 'File not found!');
		}

		$content = file_get_contents($path);

		$dir = DOCROOT.'assets'.DIRECTORY_SEPARATOR;

		// Set the proper headers to allow caching
		$this->response->headers('content-type', File::mime_by_ext($ext));
		$this->response->headers('last-modified', date('r', filemtime($path)));

		$this->response->body($content);

		// Cache the image?
		if (Kohana::$environment >= Kohana::STAGING)
		{
			return;
		}

		// Check if assets sub dir exsist.
		$parts = explode('/', $file);
		$file = array_pop($parts);
		foreach($parts as $part)
		{
			$dir .= $part.DIRECTORY_SEPARATOR;
			if( ! is_dir ($dir)) {
				mkdir($dir);
			}
		}

		file_put_contents($dir.$file, $content);
	}

}
