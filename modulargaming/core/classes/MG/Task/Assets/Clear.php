<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Clear assets cache
 *
 * @package    MG/Core
 * @category   Task
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Task_Assets_Clear extends Minion_Task {

	/**
	 * Clear the cache for assets files
	 *
	 * @param array $params
	 * @return null
	 */
	protected function _execute(array $params)
	{
		$dir = new RecursiveDirectoryIterator(DOCROOT.'assets');
		$files = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST);

		foreach($files as $file)
		{
			$filename = $file->getFilename();

			if ($filename === '.' || $filename === '..')
			{
				continue;
			}

			if ($file->isDir())
			{
				rmdir($file->getRealPath());
			}
			else
			{
				unlink($file->getRealPath());
			}
		}
	}

}
