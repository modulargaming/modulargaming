<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_User extends Controller_Frontend {

	public function action_index()
	{

	}

	public function action_login()
	{

		if ($_POST)
		{
			$auth = Auth::instance();


			$remember = isset($_POST['remember']) ? (bool) $_POST['remember'] : FALSE;

			$post = $this->request->post();

			if ($auth->login($post['username'],$post['password'], $remember))
			{
				die('logged in');
				//$this->request->redirect('');
			}
			else
			{
				die('error');
			}
		}

		// Display the login view.
		$renderer = Kostache_Layout::factory();
		$this->response->body($renderer->render(new View_User_Login));
	}


} // End User