<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * User Property model, key/value storage.
 *
 * @package    MG/User
 * @category   Model
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Model_User_Property extends ORM {

	protected $_table_columns = array(
		'id'      => NULL,
		'user_id' => NULL,
		'key'     => NULL,
		'value'   => NULL
	);

	protected $_belongs_to = array(
		'user' => array(
			'model' => 'User'
		)
	);

	protected $_serialize_columns = array(
		'value'
	);

}
