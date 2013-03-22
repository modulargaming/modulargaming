<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base controller for forum.
 *
 * @package    MG Forum
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Abstract_Controller_Forum extends Abstract_Controller_Frontend {

	protected $protected = FALSE;

	/**
	 * @var array Forum config array.
	 */
	protected $config;

	public function before()
	{
		$this->config = Kohana::$config->load('forum');

		// Check if we allow guests to view the forum in config.
		// TODO: improve this check, database based depending on category?
		if ($this->protected === FALSE AND $this->config['guest'] === FALSE)
		{
			$this->protected = TRUE;
		}

		parent::before();

		// TODO: Check if user can view forum?
	}

}
