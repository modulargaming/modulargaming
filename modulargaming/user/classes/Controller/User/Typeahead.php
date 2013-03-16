<?php defined('SYSPATH') OR die('No direct script access.');
 
class Controller_User_Typeahead extends Abstract_Controller_Ajax {

	protected $protected = TRUE;

	public function action_index()
	{
		// Load the typeahead variables.
		$username = $this->request->query('q');
		$limit = min($this->request->query('limit'), 10);

		// Query has to be longer than 3 chars.
		if (strlen($username) < 3)
		{
			return $this->response->body(json_encode(array()));
		}

		$users = ORM::factory('User')
			->where('username', 'LIKE', $username.'%')
			->limit($limit)
			->find_all();

		$usernames = array();
		foreach ($users as $user)
		{
			$usernames[] = $user->username;
		}

		$this->response->body(json_encode($usernames));
	}

}
