<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/User
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class View_Admin_User_Edit extends Abstract_View_Admin {

	public $_partials = array(
		'modal' => 'Admin/Modal/Edit'
	);

	public $user;

}
