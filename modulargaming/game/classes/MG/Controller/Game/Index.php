<?php defined('SYSPATH') OR die('No direct script access.');


class MG_Controller_Game_Index extends Abstract_Controller_Game {

	public function action_index()
	{
		$this->view = new View_Game_Index;
	}


}
