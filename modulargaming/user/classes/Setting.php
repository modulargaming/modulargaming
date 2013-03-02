<?php defined('SYSPATH') OR die('No direct script access.');
 
abstract class Setting {

	/**
	 * @var integer id of setting
	 */
	public $id;

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
	protected $views;

	public function __construct(Model_User $user)
	{
		$this->user = $user;
	}

	public function add_content(Abstract_View $view)
	{
		$this->views[] = $view;
	}

	/**
	 * @return Validation
	 */
	public abstract function get_validation();

	/**
	 * Save the user information.
	 */
	public abstract function save();

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
