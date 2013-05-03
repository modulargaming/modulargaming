<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Avatar model, used for gallery.
 *
 * @package    MG/User
 * @category   Model
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Model_Avatar extends ORM {

	protected $_belongs_to = array(
		'user' => array(
			'model' => 'User',
		)
	);

	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 30)),
				array('min_length', array(':value', 4)),
			),
			'img' => array(
				array('not_empty'),
				array('max_length', array(':value', 120)),
			),
			'default' => array(
				array('in_array', array(':value', array(0,1))),
			),
		);
	}

	public function img()
	{
		return 'assets/avatars/'.$this->img;
	}

} // End Avatar Model
