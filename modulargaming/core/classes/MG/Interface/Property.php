<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Property enabled interface for Models and misc, provides a clean way to set and get properties.
 *
 * @package    MG/Core
 * @category   Interface
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
interface MG_Interface_Property {

	/**
	 * Get a property from the key, if undefined return an empty string.
	 *
	 * @param string $key     the key to get
	 * @param mixed  $default return value if index not found
	 *
	 * @return mixed
	 */
	public function get_property($key, $default = NULL);

	/**
	 * Set a property, runs an insert query with ON DUPLICATE KEY UPDATE flag.
	 * If the property already exists mySQL swaps it to a update query.
	 *
	 * @param string  $key
	 * @param mixed   $value
	 */
	public function set_property($key, $value);

}
