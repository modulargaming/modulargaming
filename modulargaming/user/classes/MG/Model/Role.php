<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Role Model.
 *
 * @package    MG/User
 * @category   Model
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Model_Role extends Model_Auth_Role {

	// Define the role_id constants here, to prevent a database lookup.
	const LOGIN = 1;
	const ADMIN = 2;

}
