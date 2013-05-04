<?php defined('SYSPATH') OR die('No direct script access.');
 
class MG_Controller extends Kohana_Controller {

	/**
	 * @var Auth_ORM $auth
	 */
	protected $auth;

	/**
	 * @var Model_User $user Current user
	 */
	protected $user;

	/**
	 * @var bool User is required to be logged in?
	 */
	protected $protected = FALSE;

	public function before()
	{
		$this->auth = Auth::instance();
		$this->user = $this->auth->get_user();

		if ($this->protected === TRUE)
		{
			$this->logged_in_required();
		}
	}

	/**
	 * Ensure the user is logged in, else throw a 403 Exception.
	 *
	 * @throws HTTP_Exception
	 */
	protected function logged_in_required()
	{
		if ($this->auth->logged_in() == FALSE)
		{
			throw HTTP_Exception::Factory(403, 'Login to access this page!');
		}
	}

}
