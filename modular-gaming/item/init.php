<?php defined('SYSPATH') OR die('No direct script access.');

Event::listen('admin.nav_list', function(){
	return array(
		'title' => 'Items',
		'link'  => URL::site('admin/item').'/',
		'icon'  => 'icon-shopping-cart'
	);
});