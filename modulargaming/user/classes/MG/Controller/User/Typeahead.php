<?php defined('SYSPATH') OR die('No direct script access.');
 
class MG_Controller_User_Typeahead extends Abstract_Controller_Ajax {

	protected $protected = TRUE;

	/**
	 * Typeahead implementation for User username search.
	 *
	 * Finds a maximum of 10 users starting with the query.
	 */
	public function action_index()
	{
		// Load the typeahead variables.
		$username = $this->request->query('q');
		$limit = min($this->request->query('limit'), 10);

		$usernames = array();

		// Query has to be longer than 3 chars.
		if (strlen($username) > 3)
		{
			$users = ORM::factory('User')
				->where('username', 'LIKE', $username.'%')
				->limit($limit)
				->find_all();

			foreach ($users as $user)
			{
				$usernames[] = $user->username;
			}
		}

		$this->response->body(json_encode($usernames));
	}

}
