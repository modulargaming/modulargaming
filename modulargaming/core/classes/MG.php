<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * MG class.
 *
 * @package    MG/core
 * @category   MG
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG {
	public static $currency = '';

	public static function currency($name) {
		self::$currency = $name;
	}
}
