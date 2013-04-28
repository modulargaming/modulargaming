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
	 * @var Auth
	 */
	protected $_auth;

	/**
	 * @var Model_User
	 */
	protected $_user;

	public function __construct()
	{
		$this->_auth = Auth::instance();
		$this->_user = Auth::instance()->get_user();
	}

	/**
	 * Get the page title.
	 *
	 * @return string
	 */
	public function title()
	{
		return $this->title;
	}


	// TODO: We want to avoid using base_url() inside templates, remove it?
	public function base_url()
	{
		return URL::base();
	}

	public function assets_head()
	{
		return Assets::factory('head')->render();
	}

	public function assets_body()
	{
		return Assets::factory('body')->render();
	}

	/**
	 * Get the body classes for the page consiting of request directory, controller and action.
	 * @return string
	 */
	public function body_class()
	{
		$request = Request::current();

		$directory = $request->directory();
		$controller = $request->controller();

		if ($directory)
		{
			$controller = $directory.'-'.$controller;
		}

		$action = $controller.'-'.$request->action();

		return strtolower($directory.' '.$controller.' '.$action);
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
		return $this->_auth->logged_in();
	}

	/**
	 * Get the logged in users information.
	 *
	 * @return  array
	 */
	public function player()
	{
		$initial_points = Kohana::$config->load('core.initial_points');

		$user = $this->_user->as_array();

		$user['last_login'] = Date::format($user['last_login']);
		$user['created'] = Date::format($user['created']);
		$user['points'] = $this->_user->get_property('points', $initial_points);

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
				'icon'    => isset($item['icon']) ? $item['icon'] : FALSE,
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
				'href'  => '/',
				'icon'  => 'home'
			)
		);
	}

	public function has_breadcrumb()
	{
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

	/**
	 * Check if the current active user can use a policy.
	 *
	 * @param string $policy_name
	 * @param array $args
	 * @return bool
	 */
	protected function _user_can($policy_name, $args = array())
	{
		if ($this->_user === NULL)
		{
			return FALSE;
		}

		return $this->_user->can($policy_name, $args);
	}

}
