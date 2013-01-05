<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Base extends View_Base {

	function navigation()
	{
		return array(
			array(
				'title' => 'Dashboard',
				'link'  => URL::site('admin'),
				'icon'  => 'icon-home',
			),
			array(
				'title' => 'User',
				'link'  => URL::site('admin/user'),
				'icon'  => 'icon-user'
			),
			array(
				'title' => 'Forum',
				'link'  => URL::site('admin/forum'),
				'icon'  => 'icon-comment'
			)
		);
	}

}
