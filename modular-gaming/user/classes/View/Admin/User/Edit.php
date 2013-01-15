<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Edit extends Abstract_View_Admin {

	public $_partials = array(
		'modal' => 'Admin/Modal/Edit'
	);

	public $user;

}