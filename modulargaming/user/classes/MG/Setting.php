<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/User
 * @category   Setting
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
abstract class MG_Setting {

	/**
	 * @var string title for navigation
	 */
	public $title;

	/**
	 * @var string icon for navigation
	 */
	public $icon;

	/**
	 * @var Model_User
	 */
	protected $user;

	/**
	 * @var Abstract_View[]
	 */
	protected $views = array();

	public function __construct(Model_User $user)
	{
		$this->user = $user;
	}

	public function id()
	{
		return URL::title($this->title);
	}

	/**
	 * Add a new view.
	 *
	 * @param Abstract_View $view
	 */
	public function add_content(Abstract_View $view)
	{
		$this->views[] = $view;
	}

	/**
	 * Get the validation rules for the settings page.
	 *
	 * @param array $post
	 * @return Validation
	 */
	public function get_validation(array $post)
	{
		return Validation::factory($post);
	}

	/**
	 * Save the user information.
	 *
	 * @param array $post
	 */
	public abstract function save(array $post);

	/**
	 * Get the Settings page html by appending the content views.
	 *
	 * @return string
	 */
	public function view()
	{
		$renderer = Kostache::factory();
		$html = '';
		foreach ($this->views as $view)
		{
			$html .= $renderer->render($view);
		}
		return $html;
	}

}
