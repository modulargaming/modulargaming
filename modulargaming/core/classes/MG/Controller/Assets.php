<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Controller for serving assets (images, css, js) to visitors.
 * Saves the files inside assets so they can be served directly by the webserver.
 *
 * @package    MG/Core
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Controller_Assets extends Controller {

	// Extensions to cache in assets directory
	private $_cache_extensions = array(
		'jpg', 'jpeg', 'png', 'gif',	// Image extensions.
		'css',				// Cascading Style Sheets
		'js'				// Javascript
	);

	/**
	 * Serve the file to the browser AND cache it for direct access if in STAGING OR PRODUCTION.
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

		$dir = DOCROOT.'assets'.DIRECTORY_SEPARATOR;

		// Set the proper headers for browser caching
		$this->response->headers('content-type', File::mime_by_ext($ext));
		$this->response->headers('last-modified', date('r', filemtime($path)));

		$content = file_get_contents($path);
		$this->response->body($content);

		// Don't cache the assets unless we are in STAGING OR PRODUCTION.
		if (Kohana::$environment >= Kohana::STAGING)
		{
			return;
		}

		// Only cache for specific extensions.
		if ( ! in_array($ext, $this->_cache_extensions))
		{
			return;
		}

		// Check if assets sub dir exist.
		$parts = explode('/', $file);
		$file = array_pop($parts);
		foreach ($parts as $part)
		{
			$dir .= $part.DIRECTORY_SEPARATOR;
			if ( ! is_dir ($dir))
			{
				mkdir($dir);
			}
		}

		file_put_contents($dir.$file, $content);
	}

}
