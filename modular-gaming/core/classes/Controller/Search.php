<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Search extends Abstract_Controller_Frontend {

	public function action_index()
	{
		if ($this->request->is_ajax()) {
			return $this->_search($this->request->query('type'), $this->request->query('item'));
		}
		
		$this->view = new View_Search;

		if (isset($_GET['query']))
		{
			$query = $_GET['query'];

			if (strlen($query) < 3)
			{
				Hint::error('Query needs to be longer than 3 characters');
				return;
			}

			$sql_query = '%'.$query.'%';

			$users = ORM::factory('User')
				->where('username', 'LIKE', $sql_query)
				->find_all();

			// TODO: Decide how to display the search results.

			$this->view->query = $query;
			$this->view->users = $users;
		}

	}

	protected function _search($type, $item) {
		switch($type) {
			case 'user' : 
				$users = ORM::factory('User')
					->where('username', 'LIKE', '%'.$item.'%')
					->find_all();
				$output = array();
				
				foreach($users as $user) {
					$output[] = $user->username;
				}
				break;
			default:
				$output = 'ERROR';
				break;
		}
		
		$this->response->headers('Content-Type', 'application/json');
		return $this->response->body(json_encode($output));
	}
}