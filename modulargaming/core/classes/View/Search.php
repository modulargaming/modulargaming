<?php defined('SYSPATH') OR die('No direct script access.');

class View_Search extends Abstract_View {

	public $title = 'Search';

	/**
	 * @var String search query
	 */
	public $query;

	/**
	 * @var Model_User[] user search results
	 */
	public $users;

}
