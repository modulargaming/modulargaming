<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base view for normal purposes.
 *
 * @package    MG/Core
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
abstract class MG_Abstract_View {

	/**
	 * @var  string  Page title
	 */
	public $title = 'Welcome';

	/**
	 * @var string Assets group to load.
	 */
	protected $_assets_group = 'frontend';

	/**
	 * @var Auth
	 */
	protected $_auth;

	/**
	 * @var Model_User
	 */
	protected $_user;

	/**
	 * @var Assets
	 */
	protected $_assets;


	public function __construct()
	{
		$this->_auth = Auth::instance();
		$this->_user = Auth::instance()->get_user();

		$this->_assets = new Assets();
		$this->_assets->group($this->_assets_group);
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

	/**
	 * Get the assets for <head>.
	 * @return array
	 */
	public function assets_head()
	{
		return $this->_assets->get('head');
	}

	/**
	 * Get the assets for <body> (shortly before </body>).
	 * @return array
	 */
	public function assets_body()
	{
		return $this->_assets->get('body');
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
	 * Get the post values, so we can output them in forms.
	 *
	 * @return mixed
	 */
	public function post() {
		return Request::current()->post();
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
		$points = Kohana::$config->load('items.points');
		$initial_points = $points['initial'];

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
