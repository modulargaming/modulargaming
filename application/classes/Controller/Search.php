<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Search extends Controller_Frontend {

	public function action_index()
	{
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
		}

	}


}