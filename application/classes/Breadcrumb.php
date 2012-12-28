<?php defined('SYSPATH') OR die('No direct script access.');

class Breadcrumb {

	private static $data = array();

	public static function add($title, $href)
	{
		Breadcrumb::$data[] = array(
			'title' => $title,
			'href'  => $href
		);
	}

	public static function get()
	{
		return Breadcrumb::$data;
	}

}