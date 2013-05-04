<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Clear assets cache
 *
 * @package    MG/Core
 * @category   Task
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class MG_Task_Assets_Clear extends Minion_Task {
     protected $_options = array(
         'module' => '*',
	     'type' => 'js,css'
    );

	/**
	 * Clear the cache for assets files
	 *
	 * Accepts the following optional options:
	 *  - module: name of module you want to clear the asset cache for (* for all, comma separated for multiple)
	 *  - type: which type of files you want to clear (defaults to js,css)
	 *
	 * @return null
	 */
	protected function _execute(array $params)
	{
		// get the types
		$types = explode(',', $params['type']);

		// get the list of modules to clear
		if ($params['module'] == '*') {
			// get all the module paths
			$mod_paths = Kohana::modules();

			// get all the asset files
			$files = Kohana::list_files('assets', $mod_paths);

			// loop over the required types
			foreach ($types as $type) {
				if (array_key_exists('assets'.DIRECTORY_SEPARATOR.$type, $files)) {
					$this->_clear_recur($files['assets'.DIRECTORY_SEPARATOR.$type], $mod_paths);
				}
			}

			echo 'Cleared assets for all modules';
		}
		else
		{
			$modules = explode(',', $params['module']);
			// get all the module paths
			$mod_paths = Kohana::modules();

			// start looping through the modules
			foreach ($modules as $mod)
			{
				// get the base path
				$read = $mod_paths[$mod];

				// get all the asset files
				$files = Kohana::list_files('assets', array($read));

				// loop over the required types
				foreach ($types as $type)
				{
					if (array_key_exists('assets'.DIRECTORY_SEPARATOR.$type, $files))
					{
						$this->_clear_recur($files['assets'.DIRECTORY_SEPARATOR.$type], $read);
					}
				}

				echo 'Cleared assets for '.$mod;
			}
		}
	}

	protected function _clear_recur($entry, $base_dir)
	{
		if (is_array($entry))
		{
			foreach ($entry as $e)
			{
				$this->_clear_recur($e, $base_dir);
			}
		}
		else
		{
			$rm = str_replace($base_dir, '', $entry);
			if (file_exists(DOCROOT.$rm))
			{
				unlink(DOCROOT.$rm);
			}
		}
	}

}
