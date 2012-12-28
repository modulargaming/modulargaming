<?php defined('SYSPATH') OR die('No direct script access.');

class View_Base
{
	public $title = 'Welcome';

	public function base_url()
	{
		return URL::base();
	}
	
	public function csrf()
	{
		return Security::token();
	}

	public function logged_in()
	{
		return Auth::instance()->logged_in();
	}

	public function user()
	{
		$user = Auth::instance()->get_user()->as_array();
		$user['last_login'] = Date::format($user['last_login']);
		$user['created'] = Date::format($user['created']);

		return $user;
	}

	public function hints()
	{
		return Hint::get_once();
	}

	public function breadcrumb()
	{
		$breadcrumb = array();

		//Breadcrumb::get();

		foreach (Breadcrumb::get() as $item)
		{
			$breadcrumb[] = array(
				'title'   => $item['title'],
				'href'    => $item['href'],
				'active'  => $item['href'] == Request::current()->url(),
				'divider' => TRUE,
			);
		}

		// Last breadcrumb should not have a divider
		$breadcrumb[count($breadcrumb) - 1]['divider'] = FALSE;

		return $breadcrumb;
	}

	public function has_breadcrumb()
	{
		$breadcrumb = Breadcrumb::get();
		return ! empty($breadcrumb);
	}

}
