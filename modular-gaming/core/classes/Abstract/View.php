<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base view for normal purposes.
 *
 * @package    Modular Gaming
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
abstract class Abstract_View {

	/**
	 * @var  string  Page title
	 */
	public $title = 'Welcome';

	/**
	 * Get the page title.
	 *
	 * @return string
	 */
	public function title()
	{
		return $this->title;
	}

	// TODO: We want to avoid using base_url() inside templates,
	public function base_url()
	{
		return URL::base();
	}

	public function assets_head()
	{
		return Assets::head();
	}

	public function assets_body()
	{
		return Assets::body();
	}

	/**
	 * Get the current CSRF (Cross-site request forgery) token
	 *
	 * @return string
	 */
	public function csrf()
	{
		return Security::token();
	}

	/**
	 * Is the player logged in?
	 *
	 * @return boolean
	 */
	public function logged_in()
	{
		return Auth::instance()->logged_in();
	}

	/**
	 * Get the logged in users information.
	 *
	 * @return  array
	 */
	public function player()
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

		foreach ($this->get_breadcrumb() as $item)
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

	protected function get_breadcrumb()
	{
		return array(
			array(
				'title' => 'Home',
				'href'  => '/'
			)
		);
	}

	public function has_breadcrumb()
	{
		//$breadcrumb = $this->breadcrumb();
		//return ! empty($breadcrumb);
		return TRUE;
	}

	/**
	 * Return the debug toolbar template.
	 *
	 * @return  mixed
	 */
	public function debug_toolbar()
	{
		return DebugToolbar::render();
	}

}
